<?php

namespace App\Repositories\Interfaces;

use App\DTO\Vv\VvPostBackResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface FileRepositoryInterface
{
    public function saveFile(VvPostBackResponse $response): Model|Builder;
}
