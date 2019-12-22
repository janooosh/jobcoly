<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Gmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('crew@olylust.de', 'OlyLust')
        ->subject('OlyLust')
        ->markdown('mails.exmpl')
        ->with([
            'name' => 'Joe Doe',
            'link' => 'http://www.bryceandy.com'
        ]);
    }
}
