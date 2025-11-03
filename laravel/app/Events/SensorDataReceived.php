<?php
namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;

class SensorDataReceived implements ShouldBroadcast
{
    use SerializesModels;

    public $payload;
    public $deviceId;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
        $this->deviceId = $payload['device_id'] ?? null;
    }

    public function broadcastOn()
    {
        return new Channel('device.' . $this->deviceId);
    }

    public function broadcastWith()
    {
        return $this->payload;
    }

    public function broadcastAs()
    {
        return 'sensor-data';
    }
}
