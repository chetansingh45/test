<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

   public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->from('blog.noreply@mail.com')->view('Mail.reset',["data" => $this->data]);
    }
}
