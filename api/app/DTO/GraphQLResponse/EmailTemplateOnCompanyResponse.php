<?php

namespace App\DTO\GraphQLResponse;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLayout;
use Illuminate\Support\Collection;

class EmailTemplateOnCompanyResponse
{
    public ?EmailTemplateLayout $layout;

    public Collection $data;

    public static function transform(EmailTemplate $emailTemplate): self
    {
        $dto = new self();
        $dto->layout = $emailTemplate->layout;
        $dto->data = EmailTemplate::query()
            ->where('company_id', $emailTemplate->company_id)
            ->where('type', $emailTemplate->type)
            ->get();
        return $dto;
    }
}
