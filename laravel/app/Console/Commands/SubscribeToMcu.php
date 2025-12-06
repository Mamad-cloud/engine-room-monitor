<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\SensorMessage;
use App\Models\Device;
use App\Events\SensorDataReceived;

class SubscribeToMcu extends Command
{
    protected $signature = 'mcu:subscribe';
    protected $description = 'Subscribe to Redis channel mcu.data and persist + broadcast';

    public function handle()
    {
        $this->info('Starting low-level phpredis subscriber...');
        Log::info('mcu:subscribe starting');

        while (true) {
            try {
                $r = new \Redis();

                // connect (use env names used in your .env - default to "redis")
                $host = env('REDIS_HOST', 'redis');
                $port = intval(env('REDIS_PORT', 6379));

                $r->connect($host, $port);

                // important: block forever waiting for messages
                $r->setOption(\Redis::OPT_READ_TIMEOUT, -1);

                Log::info("Connected to redis for subscribe", ['host' => $host, 'port' => $port]);
                $this->info("Connected to redis for subscribe {$host}:{$port}");

                // correct callback signature for phpredis: ($redis, $channel, $message)
                $r->subscribe(['mcu.data'], function ($redisClient, $channel, $message) {
                    try {
                        // Log so docker logs show it
                        Log::info('mcu.data received', ['channel' => $channel, 'message' => $message]);
                        // also echo to stdout for immediate view
                        echo "[mcu.subscribe] {$channel} => {$message}\n";

                        // minimal write to DB (safe catch) — keep original behaviour
                        try {
                            SensorMessage::create([
                                'device_id' => 'device_id', // placeholder
                                'ts' => now()->toIso8601String(),
                                'seq' => 'seq',
                                'sensors' => [],
                                'raw' => $message,
                            ]);
                        } catch (\Throwable $e) {
                            Log::error('Failed to save SensorMessage (initial)', ['err' => $e->getMessage()]);
                        }

                        $payload = json_decode($message, true);
                        if (!is_array($payload) || empty($payload['device_id'])) {
                            Log::warning('Invalid mcu.data payload', ['raw' => $message]);
                            return;
                        }

                        // Try to find device to attach engine_room_id
                        $engineRoomId = null;
                        try {
                            $device = Device::where('device_id', $payload['device_id'])->first();
                            if ($device) {
                                $engineRoomId = $device->engine_room_id ?? null;
                            }
                        } catch (\Throwable $e) {
                            Log::error('Error querying Device', ['err' => $e->getMessage(), 'device_id' => $payload['device_id']]);
                        }

                        $sensorRecord = [
                            'device_id' => $payload['device_id'],
                            'ts' => $payload['ts'] ?? now()->toIso8601String(),
                            'seq' => $payload['seq'] ?? null,
                            'sensors' => $payload['sensors'] ?? [],
                            'engine_room_id' => $engineRoomId,
                            'raw' => $payload,
                        ];

                        // persist parsed version
                        try {
                            SensorMessage::create($sensorRecord);
                        } catch (\Throwable $e) {
                            Log::error('Failed to save parsed SensorMessage', ['err' => $e->getMessage(), 'payload' => $sensorRecord]);
                        }

                        // fire event (include engine_room_id)
                        try {
                            $payloadForEvent = $payload;
                            $payloadForEvent['engine_room_id'] = $engineRoomId;
                            $payloadForEvent['ts'] = $sensorRecord['ts'];
                            event(new SensorDataReceived($payloadForEvent));
                        } catch (\Throwable $e) {
                            Log::error('Failed to broadcast SensorDataReceived', ['err' => $e->getMessage()]);
                        }
                    } catch (\Throwable $e) {
                        Log::error('Error inside subscribe callback', ['err' => $e->getMessage()]);
                    }
                });

                // subscribe returned or ended: break to retry outer loop if needed
                Log::warning('Redis subscribe returned (clean exit). Will reconnect in 3s.');
            } catch (\Throwable $ex) {
                $this->error('Subscriber exception: ' . $ex->getMessage());
                Log::error('Subscriber exception', ['err' => $ex->getMessage()]);
            }

            sleep(3); // short backoff then retry connecting
        }

        return 0;
    }
}
