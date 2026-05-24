<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResendVerificationEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $first_name;
    public $last_name;
    public $link;

    public function __construct($email, $first_name, $last_name, $link)
    {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('Resend Email Verification')
            ->html(
                '
                    <h2>Hello ' . $this->first_name . ' ' . $this->last_name . '</h2>
                    <p>You requested to resend your email verification link.</p>
                    <p>Please verify your email by clicking the link below:</p>
                    <a href="' . $this->link . '">Verify Account</a>
                '
            );
    }
}