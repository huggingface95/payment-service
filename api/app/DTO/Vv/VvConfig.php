<?php

namespace App\DTO\Vv;

class VvConfig
{
    public object $routes;

    public string $appName;

    public string $name;

    public string $host;

    public string $appToken;

    public string $postBackUrl;

    public static function transform(array $vvConfig, array $appConfig): self
    {
        $dto = new self();
        $dto->routes = (object) $vvConfig['routes'];
        $dto->name = $vvConfig['name'];
        $dto->appName = $appConfig['name'];
        $dto->host = $vvConfig['host'];
        $dto->appToken = $vvConfig['token'];
        $dto->postBackUrl = $vvConfig['post_back_url'];

        return $dto;
    }
}
