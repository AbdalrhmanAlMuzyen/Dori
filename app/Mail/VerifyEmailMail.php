<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

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

public function build()
{
    return $this->subject('Email Verification')
        ->html(
            '
                <h2>Hello ' . $this->first_name . ' ' . $this->last_name . '</h2>
                <p>Please verify your email by clicking the link below:</p>
                <a href="' . $this->link . '">' . $this->link . '</a>
            '
        );
}

}
