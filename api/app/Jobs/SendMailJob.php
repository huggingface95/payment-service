<?php

namespace App\Jobs;

use App\DTO\Email\SendEmailRequestDTO;
use Illuminate\Support\Facades\Mail;

class SendMailJob extends Job
{

    protected SendEmailRequestDTO $dto;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SendEmailRequestDTO $sendEmailRequestDTO)
    {
        $this->dto = $sendEmailRequestDTO;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        Mail::send([], [], function ($message) {
            //TODO change real administration email
            $message->to('arthurgevorgyan1992@gmail.com')
                ->subject($this->dto->subject)
                ->setBody($this->dto->content, 'text/html');
        });
    }
}
