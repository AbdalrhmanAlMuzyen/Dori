<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendFireBaseNotificationEvent implements ShouldDispatchAfterCommit
{
    public $notification;
    public $device_tokens;

    public function __construct($notification,$device_tokens)
    {
        $this->notification=$notification;
        $this->device_tokens=$device_tokens;
    }
}
