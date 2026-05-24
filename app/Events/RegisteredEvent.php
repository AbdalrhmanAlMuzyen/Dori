<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegisteredEvent implements ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $email;
    public $first_name;
    public $last_name;
    public $link;

    public function __construct($email,$first_name,$last_name,$link)
    {
        $this->email=$email;
        $this->first_name=$first_name;
        $this->last_name=$last_name;
        $this->link=$link;
    }
}
