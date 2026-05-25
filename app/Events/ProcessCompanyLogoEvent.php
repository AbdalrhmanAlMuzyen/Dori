<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessCompanyLogoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public object $company;
    public string $logo_path;

    public function __construct(object $company , string $logo_path)
    {
        $this->company=$company;
        $this->logo_path=$logo_path;
    }
}
