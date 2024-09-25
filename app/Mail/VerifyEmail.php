<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

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
            ->subject('Xác thực email của bạn')
            ->view('emails.verify-email')
            ->with([
                'verificationUrl' => url('/verify-email/' . $this->token),
                'userName' => $this->user->user_name,
            ]);
    }
}
