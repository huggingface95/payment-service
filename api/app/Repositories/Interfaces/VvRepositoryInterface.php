<?php

namespace App\Repositories\Interfaces;

use App\DTO\Vv\Request\VvGetLinkRequest;
use App\DTO\Vv\Request\VvRegisterRequest;
use App\DTO\Vv\VvConfig;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface VvRepositoryInterface
{

    public function findByToken(string $token): Model|Builder|null;

    public function findById(int $id): Model|Builder|null;

    public function hasToken(string $token): bool;

    public function saveToken(int $id, string $token): bool;

    public function getDtoRegisterCompanyRequest(int $id, VvConfig $config): VvRegisterRequest;

    public function getDtoGetLinkRequest(int $id, string $action, VvConfig $config): VvGetLinkRequest;

}

