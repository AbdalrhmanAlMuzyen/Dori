<?php

namespace App\Listeners;

use App\Events\SendFireBaseNotificationEvent;
use App\Jobs\SendFirebasNotificationJob;

class SendFirebaseNotificationListener
{
    public function handle(SendFireBaseNotificationEvent $event): void
    {
        dispatch(new SendFirebasNotificationJob($event->notification,$event->device_tokens));
    }
}
