<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class Absage extends Mailable
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
        ->subject('Deine Schicht auf der OlyLust')
        ->markdown('mails.absage')
        ->with([
            'user' => Auth::user()->firstname,
            'link' => 'http://crew.olylust.de/assignments/my'
        ]);
    }
}
