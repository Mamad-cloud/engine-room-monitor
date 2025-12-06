<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class SensorDataReceived implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function broadcastWith()
    {
        return $this->payload;
    }

    /**
     * Broadcast to device.{device_id} and engine-room.{engine_room_id} if present.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];

        $deviceId = $this->payload['device_id'] ?? null;
        if ($deviceId) {
            $channels[] = new Channel('device.' . $deviceId);
        }

        $er = $this->payload['engine_room_id'] ?? null;
        if (!empty($er)) {
            $channels[] = new Channel('engine-room.' . $er);
        }

        if (empty($channels)) {
            $channels[] = new Channel('devices');
        }

        return $channels;
    }

    public function broadcastAs()
    {
        return 'sensor-data';
    }
}
