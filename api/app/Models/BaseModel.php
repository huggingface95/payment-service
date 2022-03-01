<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    const DEFAULT_MEMBER_ID = 2;

    protected function setArrayAttribute($value)
    {
        return  str_replace(['[', ']'], ['{', '}'], json_encode($value));
    }

    protected function getArrayAttribute($value)
    {
        return json_decode(str_replace(['{', '}'], ['[', ']'], $value));
    }


}
