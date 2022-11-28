<?php

namespace App\Repositories\Interfaces;

use App\DTO\Email\SmtpDataDTO;
use App\Models\Account;
use App\Models\Members;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface EmailRepositoryInterface
{
    public function getSmtpByCompanyId(Account|Members $account): Model|Builder;

    public function getSmtpByMemberId(Members $members): Model|Builder;

    public function getTemplateContentAndSubject(Account $account): SmtpDataDTO;
}
