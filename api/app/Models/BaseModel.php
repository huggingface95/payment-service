<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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


    protected static function booted()
    {
        parent::booted();

        /** @var Members $member */
        if ($member = Auth::user()){
            $ids = $member->accessLimitations()->get()->pluck('groupRole')->map(function ($role){
                return $role->users;
            })->flatten(1)->pluck('id')->toArray();

            static::addGlobalScope(new ApplicantFilterByMemberScope($ids, $member->is_show_owner_applicants));
        }
    }

}
