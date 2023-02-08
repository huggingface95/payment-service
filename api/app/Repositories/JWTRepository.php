<?php

namespace App\Repositories;

use App\Repositories\Interfaces\JWTRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Client;

class JWTRepository implements JWTRepositoryInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getPersonalAccessToken(string $provider): Model|Builder|null
    {
        return $this->client->newQuery()
            ->where('provider', $provider)
            ->where('personal_access_client', true)
            ->where('password_client', false)
            ->where('revoked', false)->first();
    }
}
