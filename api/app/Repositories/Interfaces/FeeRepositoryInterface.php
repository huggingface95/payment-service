<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface FeeRepositoryInterface
{
    public function getFeeByTypeMode(int $transferId, int $type): Model|Builder|null;
}
