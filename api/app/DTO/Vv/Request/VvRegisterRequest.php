<?php

namespace App\DTO\Vv\Request;

use App\DTO\Vv\VvConfig;
use App\Models\Companies;

class VvRegisterRequest
{
    public string $url;

    public array $headers;

    public string $inputs;

    public static function transform(VvConfig $config, Companies $company): self
    {
        $dto = new self();
        $dto->url = sprintf($config->routes->register, $config->host, $config->appName, $company->id);
        $dto->headers['token'] = $config->appToken;

        return $dto;
    }
}
