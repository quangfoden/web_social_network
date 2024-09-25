<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail  extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $token;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $token)
    {
        //
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->from('nguyenvanquang22103@gmail.com', 'Devonline')
            ->subject('Đặt lại mật khẩu')
            ->view('emails.reset-password')
            ->with([
                'userName' => $this->user->user_name,
                'resetLink' => url('/reset-password/' . $this->token . '?email=' . $this->user->email),
            ]);
    }
}
