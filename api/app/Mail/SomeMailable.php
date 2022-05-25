<?php

namespace App\Mail;

use App\DTO\Email\SmtpDataDTO;
use Illuminate\Mail\Mailable;

class SomeMailable extends Mailable
{
    public $html;

    public function __construct(SmtpDataDTO $smtpDataDTO)
    {
        $this->html = $smtpDataDTO->body;
    }

    public function build(){

    }
}
