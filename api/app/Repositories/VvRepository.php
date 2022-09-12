<?php

namespace App\Repositories;

use App\DTO\TransformerDTO;
use App\DTO\Vv\Request\VvGetLinkRequest;
use App\DTO\Vv\Request\VvRegisterRequest;
use App\DTO\Vv\VvConfig;
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

    public function findByToken(string $token): Model|Builder|null
    {
        return $this->find(['vv_token' => $token]);
    }

    public function findById(int $id): Model|Builder|null
    {
        return $this->find(['id' => $id]);
    }

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

    public function getDtoRegisterCompanyRequest(int $id, VvConfig $config): VvRegisterRequest{
        $company = $this->findById($id);

        return TransformerDTO::transform(VvRegisterRequest::class, $config, $company);
    }

    public function getDtoGetLinkRequest(int $id, string $action, VvConfig $config): VvGetLinkRequest{
        $company = $this->findById($id);

        return TransformerDTO::transform(VvGetLinkRequest::class, $config, $company, $action);
    }

}
