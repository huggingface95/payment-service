<?php

namespace App\DTO\Vv\Request;

use App\DTO\Vv\VvConfig;
use App\Models\Company;

class VvGetLinkRequest
{

    public string $url;

    public array $headers;

    public array $inputs;

    public static function transform(VvConfig $config, Company $company, string $action, string $image = null): self
    {
        $dto = new self();
        $dto->url = sprintf($config->routes->preparation, $config->host);
        $dto->headers['service'] = $config->appToken;
        $dto->headers['company'] = $company->companySettings->vv_token;
        $dto->inputs['action'] = $action;
        $dto->inputs['post_back_url'] = $config->postBackUrl;
        if ($image) {
            $dto->inputs['image'] = $image;
        }

        return $dto;
    }
}
