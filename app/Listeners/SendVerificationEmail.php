<?php

namespace App\Listeners;

use App\Events\RegisteredEvent;
use App\Jobs\SendVerificationEmailJob;

class SendVerificationEmail 
{
    public function handle(RegisteredEvent $event): void
    {
        dispatch(new SendVerificationEmailJob($event->email,$event->first_name,$event->last_name,$event->link));
    }
}
