<?php

namespace App\DTO\GraphQLMutator;

use Illuminate\Support\Str;

class ActiveSessionCloneDTO
{
    public string $id;

    public string $provider;

    public string $email;

    public string $company;

    public string $ip;

    public string $platform;

    public string $browser;

    public string $browser_version;

    public string $device_type;

    public string $model;

    public string $country;

    public string $city;

    public string $active;

    public string $trusted;

    public string $cookie;

    public static function transform(array $data, bool $trusted): self
    {
        $dto = new self();
        $dto->id = Str::uuid();
        $dto->provider = $data['provider'];
        $dto->email = $data['email'];
        $dto->company = $data['company'];
        $dto->ip = $data['ip'];
        $dto->platform = $data['platform'];
        $dto->browser = $data['browser'];
        $dto->browser_version = $data['browser_version'];
        $dto->device_type = $data['device_type'];
        $dto->model = $data['model'];
        $dto->country = $data['country'];
        $dto->city = $data['city'];
        $dto->cookie = $data['cookie'];
        $dto->trusted = $trusted;

        return $dto;
    }
}
