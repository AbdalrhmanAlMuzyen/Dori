<?php

namespace App\Jobs;

use App\Mail\ResetPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordEmailJob implements ShouldQueue
{
    use Queueable;

    public string $first_name;
    public string $last_name;
    public string $email;
    public string $link;

    public function __construct(string $first_name, string $last_name,string $email,string $link)
    {
        $this->first_name=$first_name;
        $this->last_name=$last_name;
        $this->email=$email;
        $this->link=$link;
    }

    public function handle(): void
    {
        Mail::to($this->email)->send(new ResetPasswordMail($this->first_name,$this->last_name,$this->email,$this->link));
    }
}
