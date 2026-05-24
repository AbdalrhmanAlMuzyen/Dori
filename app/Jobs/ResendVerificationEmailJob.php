<?php

namespace App\Jobs;

use App\Mail\ResendVerificationEmailMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ResendVerificationEmailJob implements ShouldQueue
{
    use Queueable;

    public string $email;
    public string $first_name;
    public string $last_name;
    public string $link;

    public function __construct(string $email,string $first_name,string $last_name,string $link)
    {
        $this->email=$email;
        $this->first_name=$first_name;
        $this->last_name=$last_name;
        $this->link=$link;
    }

    public function handle(): void
    {
        Mail::to($this->email)->send(new ResendVerificationEmailMail($this->email,$this->first_name,$this->last_name,$this->link));
    }
}