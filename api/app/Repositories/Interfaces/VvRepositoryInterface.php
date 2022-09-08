<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface VvRepositoryInterface
{
    public function findByToken(string $token): Model|Builder|null;

    public function hasToken(string $token): bool;

}

