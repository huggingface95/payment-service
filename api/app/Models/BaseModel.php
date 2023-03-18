<?php

namespace App\Models;

use App\Models\Scopes\FilterByCompanyScope;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public const DEFAULT_MEMBER_ID = 2;

    public const SUPER_COMPANY_ID = 1;

    //Access limitation applicant ids
    public static ?array $applicantIds = null;

    public static ?int $currentCompanyId = null;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new FilterByCompanyScope());
    }

    protected static function booting()
    {
        parent::booting();
    }

    protected function setArrayAttribute($value)
    {
        return str_replace(['[', ']'], ['{', '}'], json_encode($value));
    }

    protected function getArrayAttribute($value)
    {
        return json_decode(str_replace(['{', '}'], ['[', ']'], $value));
    }
}
