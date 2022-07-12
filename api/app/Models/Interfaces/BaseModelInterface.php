<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface BaseModelInterface
{
    public static function getAccountFilter($filter):Builder;
}
