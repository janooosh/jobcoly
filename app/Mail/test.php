<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class test extends Mailable
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
    public function build($p)
    {
        return $this->from('olylust2019@gmail.com', 'OlyLust')
        ->subject('OlyLust')
        ->markdown('mails.exmpl')
        ->with([
            'was' => $p,
            'link' => 'http://www.bryceandy.com'
        ]);
    }
}