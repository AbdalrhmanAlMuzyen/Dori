<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use SerializesModels;

    public string $first_name;
    public string $last_name;
    public string $email;
    public string $link;

    public function __construct(string $first_name, string $last_name, string $email, string $link)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->link = $link;
    }

    public function build()
    {
        return $this
            ->subject('Reset Password Request')
            ->html("
                <html>
                    <body style='font-family: Arial; background:#f4f4f4; padding:20px;'>

                        <div style='max-width:600px;margin:auto;background:#fff;padding:25px;border-radius:10px;'>

                            <h2>Hello {$this->first_name} {$this->last_name} 👋</h2>

                            <p>You requested to reset your password.</p>

                            <p>Please click the button below to reset it:</p>

                            <div style='text-align:center;margin:30px 0;'>
                                <a href='{$this->link}' 
                                   style='padding:12px 20px;background:#2d89ef;color:#fff;text-decoration:none;border-radius:6px;'>
                                   Reset Password
                                </a>
                            </div>

                            <p style='color:#999;font-size:13px;'>
                                If you didn't request this, you can ignore this email.
                            </p>

                            <hr>

                            <p style='font-size:12px;color:#aaa;text-align:center;'>
                                © " . "Dori" . "
                            </p>

                        </div>

                    </body>
                </html>
            ");
    }
}