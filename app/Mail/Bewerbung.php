<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        return $this->from('olylust2019@gmail.com', 'OlyLust')
        ->subject('OlyLust')
        ->markdown('mails.bewerbung')
        ->with([
            'name' => Auth::user()->firstname,
            'link' => 'http://crew.olylust.de/applications'
        ]);
    }
}
