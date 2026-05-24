<?php

namespace App\Listeners;

use App\Events\SendResetPasswordEmailEvent;
use App\Jobs\SendResetPasswordEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendResetPasswordEmailListener
{
    public function handle(SendResetPasswordEmailEvent $event): void
    {
        dispatch(new SendResetPasswordEmailJob($event->first_name,$event->last_name,$event->email,$event->link));
    }
}
