<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPMailer extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $otp;

    /**
     * OTPMailer constructor.
     * @param string $otp
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New One Time PIN')
            ->view('otp_mail')
            ->with(['otp' => $this->otp]);
    }
}
