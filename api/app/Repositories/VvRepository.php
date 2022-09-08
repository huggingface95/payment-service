<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\ApplicantIndividual;
use App\Repositories\Interfaces\VvRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VvRepository extends Repository implements VvRepositoryInterface
{
    protected function model(): string
    {
        return ApplicantIndividual::class;
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

}
