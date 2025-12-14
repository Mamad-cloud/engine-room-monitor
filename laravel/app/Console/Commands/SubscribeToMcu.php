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
                        Log::info('mcu.data received', ['channel' => $channel, 'message' => $message]);
                        echo "[mcu.subscribe] {$channel} => {$message}\n";

                        $payload = json_decode($message, true);
                        if (!is_array($payload) || empty($payload['device_id']) || empty($payload['event_id'])) {
                            Log::warning('Invalid mcu.data payload (missing device_id or event_id)', ['raw' => $message]);
                            return;
                        }

                        $deviceId = (string) $payload['device_id'];
                        $eventId = intval($payload['event_id']);
                        $ts = $payload['timestamp'] ?? ($payload['ts'] ?? now()->toIso8601String());
                        $items = $payload['peripherals'] ?? [];

                        // find device (optional; used for engine_room_id)
                        $engineRoomId = null;
                        try {
                            $device = \App\Models\Device::where('device_id', $deviceId)->first();
                            if ($device) {
                                $engineRoomId = $device->engine_room_id ?? null;
                            }
                        } catch (\Throwable $e) {
                            Log::error('Device lookup failed', ['err' => $e->getMessage(), 'device_id' => $deviceId]);
                            $device = null;
                        }

                        // helper to resolve a PeripheralMode from modeIdentifier
                        $resolveMode = function ($modeIdentifier) {
                            // try as direct _id (string/objectid)
                            try {
                                $mode = \App\Models\PeripheralMode::find($modeIdentifier);
                                if ($mode) return $mode;
                            } catch (\Throwable $e) {
                                // ignore
                            }

                            // try legacy numeric id field (optional)
                            try {
                                if (is_numeric($modeIdentifier)) {
                                    $mode = \App\Models\PeripheralMode::where('legacy_id', intval($modeIdentifier))->first();
                                    if ($mode) return $mode;
                                }
                            } catch (\Throwable $e) {
                            }

                            // try by slug or mode name
                            try {
                                $mode = \App\Models\PeripheralMode::where('slug', (string)$modeIdentifier)
                                    ->orWhere('mode', (string)$modeIdentifier)
                                    ->first();
                                if ($mode) return $mode;
                            } catch (\Throwable $e) {
                            }

                            return null;
                        };

                        // parse the device-specific token like "1_1_0" -> [modeId, index, value]
                        $parseItem = function ($item) {
                            // ensure scalar string
                            if (!is_scalar($item)) return null;
                            $s = (string) $item;
                            // limit to 3 parts; 3rd may contain underscores (we want the remainder)
                            $parts = explode('_', $s, 3);
                            if (count($parts) < 3) return null;
                            return [
                                'mode_identifier' => $parts[0],
                                'index' => intval($parts[1]),
                                'value_raw' => $parts[2],
                            ];
                        };

                        // EVENT: register/update (1,3) -> payload value is name; sync peripherals
                        if ($eventId === 1 || $eventId === 3) {
                            foreach ($items as $raw) {
                                $p = $parseItem($raw);
                                if (!$p) {
                                    Log::warning('Malformed peripheral item on register/update', ['raw' => $raw, 'device' => $deviceId]);
                                    continue;
                                }

                                $mode = $resolveMode($p['mode_identifier']);
                                $modeId = $mode ? $mode->_id : null;

                                $index = $p['index'];
                                $nameFromDevice = $p['value_raw'];

                                // upsert peripheral by device_id + index + mode
                                try {
                                    $existing = \App\Models\Peripheral::where('device_id', $deviceId)
                                        ->where('index', $index)
                                        ->where('mode_id', $modeId)
                                        ->first();

                                    $attrs = [
                                        'device_id' => $deviceId,
                                        'mode_id' => $modeId,
                                        'index' => $index,
                                        'name' => $nameFromDevice ?: ($mode ? ($mode->mode . '_' . $index) : ('p' . $index)),
                                        'persian_label' => null,
                                        'active' => true,
                                        'engine_room_id' => $engineRoomId,
                                    ];

                                    if ($existing) {
                                        $existing->update($attrs);
                                    } else {
                                        \App\Models\Peripheral::create($attrs);
                                    }
                                } catch (\Throwable $e) {
                                    Log::error('Failed upserting peripheral (register/update)', ['err' => $e->getMessage(), 'device' => $deviceId, 'index' => $index]);
                                }
                            }

                            // mark missing indexes as inactive (optional)
                            try {
                                $reportedIndexes = [];
                                foreach ($items as $raw) {
                                    $p = $parseItem($raw);
                                    if ($p) $reportedIndexes[] = $p['index'];
                                }
                                if (!empty($reportedIndexes)) {
                                    \App\Models\Peripheral::where('device_id', $deviceId)
                                        ->whereNotIn('index', $reportedIndexes)
                                        ->update(['active' => false]);
                                }
                            } catch (\Throwable $e) {
                                Log::error('Failed to mark missing peripherals inactive', ['err' => $e->getMessage(), 'device' => $deviceId]);
                            }

                            Log::info('Peripherals synced for device', ['device_id' => $deviceId]);
                            return;
                        }

                        // EVENT: telemetry/state update (2) -> value is state; store PeripheralState
                        if ($eventId === 2) {
                            foreach ($items as $raw) {
                                $p = $parseItem($raw);
                                if (!$p) {
                                    Log::warning('Malformed peripheral item on telemetry', ['raw' => $raw, 'device' => $deviceId]);
                                    continue;
                                }

                                $index = $p['index'];
                                $modeIdentifier = $p['mode_identifier'];
                                $valueRaw = $p['value_raw'];

                                // attempt to find existing peripheral for device+index

                                $mode = $resolveMode($modeIdentifier);
                                $modeId = $mode ? $mode->_id : null;
                                try {
                                    $per = \App\Models\Peripheral::where('device_id', $deviceId)
                                        ->where('index', $index)
                                        ->where('mode_id', $modeId)
                                        ->first();
                                } catch (\Throwable $e) {
                                    Log::error('Peripheral lookup failed', ['err' => $e->getMessage(), 'device' => $deviceId, 'index' => $index]);
                                    $per = null;
                                }

                                // if peripheral not exists, create placeholder (mode may be resolved from identifier)
                                if (!$per) {
                                    $mode = $resolveMode($modeIdentifier);
                                    $modeId = $mode ? $mode->_id : null;
                                    try {
                                        $per = \App\Models\Peripheral::create([
                                            'device_id' => $deviceId,
                                            'index' => $index,
                                            'mode_id' => $modeId,
                                            'name' => $mode ? ($mode->mode . '_' . $index) : ('p' . $index),
                                            'persian_label' => null,
                                            'active' => true,
                                            'engine_room_id' => $engineRoomId,
                                        ]);
                                    } catch (\Throwable $e) {
                                        Log::error('Failed creating placeholder peripheral', ['err' => $e->getMessage(), 'device' => $deviceId, 'index' => $index]);
                                        continue;
                                    }
                                }

                                // convert value to proper type: try integer -> float -> boolean-like -> leave string
                                $stateValue = $valueRaw;
                                if (is_numeric($valueRaw)) {
                                    // maintain integer if no decimal point, else float
                                    $stateValue = (strpos($valueRaw, '.') !== false) ? floatval($valueRaw) : intval($valueRaw);
                                } else {
                                    $low = strtolower($valueRaw);
                                    if (in_array($low, ['true', '1', 'on', 'yes'])) $stateValue = 1;
                                    elseif (in_array($low, ['false', '0', 'off', 'no'])) $stateValue = 0;
                                }

                                // store state
                                try {
                                    \App\Models\PeripheralState::create([
                                        'peripheral_id' => $per->_id,
                                        'device_id' => $deviceId,
                                        'state' => $stateValue,
                                        'ts' => $ts,
                                        'engine_room_id' => $engineRoomId,
                                        'meta' => $payload['meta'] ?? null,
                                    ]);
                                } catch (\Throwable $e) {
                                    Log::error('Failed saving PeripheralState', ['err' => $e->getMessage(), 'device' => $deviceId, 'index' => $index]);
                                }

                                // broadcast the new peripheral state
                                try {
                                    $evtPayload = [
                                        'device_id' => $deviceId,
                                        'peripheral_id' => (string)$per->_id,
                                        'index' => $per->index,
                                        'state' => $stateValue,
                                        'ts' => $ts,
                                        'engine_room_id' => $engineRoomId,
                                        'name' => $per->name,
                                    ];
                                    event(new \App\Events\PeripheralStateUpdated($evtPayload));
                                } catch (\Throwable $e) {
                                    Log::error('Failed broadcasting PeripheralStateUpdated', ['err' => $e->getMessage(), 'device' => $deviceId, 'index' => $index]);
                                }
                            }

                            return;
                        }

                        // fallback: if not handled, persist whole message in SensorMessage
                        try {
                            \App\Models\SensorMessage::create([
                                'device_id' => $deviceId,
                                'ts' => $ts,
                                'seq' => $payload['seq'] ?? null,
                                'sensors' => $payload['sensors'] ?? [],
                                'engine_room_id' => $engineRoomId,
                                'raw' => $payload,
                            ]);
                        } catch (\Throwable $e) {
                            Log::error('Failed saving fallback SensorMessage', ['err' => $e->getMessage()]);
                        }
                    } catch (\Throwable $e) {
                        Log::error('Error inside subscribe callback', ['err' => $e->getMessage()]);
                    }
                });
                // ----------------- end callback paste -----------------


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
