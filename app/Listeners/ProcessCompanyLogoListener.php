<?php

namespace App\Listeners;

use App\Events\ProcessCompanyLogoEvent;
use App\Jobs\ProcessCompanyLogoJob;

class ProcessCompanyLogoListener
{

    public function handle(ProcessCompanyLogoEvent $event): void
    {
        dispatch(new ProcessCompanyLogoJob($event->company,$event->logo_path));
    }
}
