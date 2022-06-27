<?php

namespace App\Mail;

use App\DTO\Email\SmtpDataDTO;
use Illuminate\Mail\Mailable;

class SomeMailable extends Mailable
{
    public $html;

    public $subject;

    public function __construct(SmtpDataDTO $smtpDataDTO)
    {
        $this->html = $smtpDataDTO->body;
        $this->subject = $smtpDataDTO->subject;
    }

    public function build()
    {
    }
}
