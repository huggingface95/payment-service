<?php

namespace App\GraphQL\Types;

use Illuminate\Support\Carbon;
use Nuwave\Lighthouse\Schema\Types\Scalars\DateScalar;

class DateEnd extends DateScalar
{
    protected function format(Carbon $carbon): string
    {
        return $carbon->toDateString();
    }

    protected function parse($value): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $value)->endOfDay();
    }
}
