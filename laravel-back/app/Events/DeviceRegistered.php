<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class DeviceRegistered implements ShouldBroadcast
{
    use SerializesModels;

    public $device;

    public function __construct($device)
    {
        $this->device = $device;
    }

    public function broadcastOn()
    {
        return new Channel('devices');
    }

    public function broadcastWith()
    {
        return ['device' => $this->device];
    }

    public function broadcastAs()
    {
        return 'device-registered';
    }
}
