<?php

namespace App\Repositories;

use App\Models\Fee;
use App\Repositories\Interfaces\FeeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FeeRepository extends Repository implements FeeRepositoryInterface
{
    protected function model(): string
    {
        return Fee::class;
    }

    public function getFeeByTypeMode(int $transferId, int $type): Model|Builder|null
    {
        return $this->find([
            ['transfer_id', $transferId],
            ['fee_type_mode_id', $type],
        ]);
    }
}
