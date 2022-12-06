<?php

namespace App\DTO\Vv\Request;

use App\DTO\Vv\VvConfig;
use App\Models\Company;

class VvRegisterRequest
{
    public string $url;

    public array $headers;

    public string $inputs;

    public static function transform(VvConfig $config, Company $company): self
    {
        $dto = new self();
        $dto->url = sprintf($config->routes->register, $config->host, $config->appName, $company->id);
        $dto->headers['token'] = $config->appToken;

        return $dto;
    }
}
