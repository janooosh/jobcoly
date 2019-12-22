<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class Bewerbung extends Mailable
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
        ->subject('Vielen Dank für deine Bewerbung!')
        ->markdown('mails.bewerbung')
        ->with([
            'user' => Auth::user()->firstname,
            'link' => 'http://crew.olylust.de/applications'
        ]);
    }
}
