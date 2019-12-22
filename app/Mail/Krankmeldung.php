<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class Krankmeldung extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('crew@olylust.de', 'OlyLust')
        ->subject('Krankmeldung / OlyLust')
        ->markdown('mails.krankmeldung')
        ->with([
            'user' => Auth::user()->firstname,
            'link' => 'http://crew.olylust.de/assignments/my'
        ]);
    }
}
