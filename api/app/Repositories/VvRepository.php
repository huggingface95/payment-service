<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\CompanySettings;
use App\Repositories\Interfaces\VvRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VvRepository extends Repository implements VvRepositoryInterface
{
    protected function model(): string
    {
        return CompanySettings::class;
    }

    /**
     * @throws RepositoryException
     */
    public function findByToken(string $token): Builder|Model|null
    {
        return $this->query()->where('vv_token', $token)->first();
    }

    /**
     * @throws RepositoryException
     */
    public function hasToken(string $token): bool
    {
        return (bool) $this->findByToken($token);
    }

    /**
     * @throws RepositoryException
     */
    public function saveToken(int $id, string $token): bool{
        return (bool) $this->query()->updateOrCreate([
            'company_id'   => $id,
        ],[
            'vv_token'     => $token,
        ]);
    }

}
