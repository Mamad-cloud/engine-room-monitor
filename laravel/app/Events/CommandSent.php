<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CommandSent implements ShouldBroadcast
{
    use SerializesModels;

    public $payload;
    public $deviceId;

    /**
     * Create a new event instance.
     *
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
        $this->deviceId = $payload['device_id'] ?? null;
    }

    public function broadcastOn()
    {
        // public channel device.{device_id}
        return new Channel('device.' . $this->deviceId);
    }

    public function broadcastWith()
    {
        return $this->payload;
    }

    public function broadcastAs()
    {
        return 'command-sent';
    }
}
