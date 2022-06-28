<?php

namespace App\Jobs;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\Mail\SomeMailable;
use Illuminate\Mail\Mailer;

class SendMailJob extends Job
{
    protected SmtpDataDTO $dataDTO;

    protected SmtpConfigDTO $configDTO;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SmtpConfigDTO $configDTO, SmtpDataDTO $dataDTO)
    {
        $this->dataDTO = $dataDTO;
        $this->configDTO = $configDTO;
    }

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        /** @var Mailer $mailer */
        //$mailer = app()->makeWith('smtp.dynamic.mailer', (array)$this->configDTO);
        //$mailer->to($this->dataDTO->to)->send(new SomeMailable($this->dataDTO));
    }
}
