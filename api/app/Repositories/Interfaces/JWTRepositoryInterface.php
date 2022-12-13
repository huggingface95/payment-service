<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface JWTRepositoryInterface
{
    public function getPersonalAccessToken(string $provider): Model|Builder|null;
}
