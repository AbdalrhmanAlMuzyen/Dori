<?php

namespace App\Jobs;

use App\Mail\VerifyEmailMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmailJob implements ShouldQueue
{
    use Queueable;

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

    public function handle(): void
    {
        Mail::to($this->email)->send(new VerifyEmailMail($this->email,$this->first_name,$this->last_name,$this->link));
    }
}
