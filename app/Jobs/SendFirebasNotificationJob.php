<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendFirebasNotificationJob implements ShouldQueue
{
    use Queueable;

    public $notification;
    public $device_tokens;

    public function __construct($notification,$device_tokens)
    {
        $this->notification=$notification;
        $this->device_tokens=$device_tokens;
    }

    public function handle(): void
    {
        Http::withHeaders([
            "Authorization"=>"key=" . config('services.firebase.server_key'),
            "Content-Type"=>'َapplication/json',
        ])->post("https://fcm.googleapis.com/fcm/send",[
            'registration_ids'=>$this->device_tokens,
            "notification"=>[
                "title"=>$this->notification->title,
                "body"=>$this->notification->body
            ],
            "data"=>[
                "target_id"=>$this->notification->target_id,
                "type"=>$this->notification->type
            ]
        ]);
    }
}
