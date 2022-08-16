<?php

namespace App\DTO\GraphQLResponse;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLayout;

class EmailTemplateOnCompanyResponse
{
    public ?EmailTemplateLayout $layout;

    public array $data;

    public static function transform(EmailTemplate $emailTemplate): self
    {
        $dto = new self();
        $dto->layout = $emailTemplate->layout;
        $dto->data = EmailTemplate::query()
            ->where('company_id', $emailTemplate->company_id)
            ->where('type', $emailTemplate->type)
            ->get()->toArray();
        return $dto;
    }
}
