<?php

namespace App\Listeners;

use App\Events\ResendVerificationEmailEvent;
use App\Jobs\ResendVerificationEmailJob;

class ResendVerificationEmailListener
{
    public function handle(ResendVerificationEmailEvent $event): void
    {
        dispatch(new ResendVerificationEmailJob($event->email,$event->first_name,$event->last_name,$event->link));
    }
}