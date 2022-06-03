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
        return str_replace(['[', ']'], ['{', '}'], json_encode($value));
    }

    protected function getArrayAttribute($value)
    {
        return json_decode(str_replace(['{', '}'], ['[', ']'], $value));
    }


    protected static function getApplicantIdsByAuthMember(): ?array
    {
        /** @var Members $member */
        if ($member = Auth::user()) {
            if ($member->IsShowOwnerApplicants()) {
                return [
                    'applicant_individual' => $member->accountManagerApplicantIndividuals()->get()->pluck('id'),
                    'applicant_companies' => $member->accountManagerApplicantCompanies()->get()->pluck('id'),
                ];
            } else {
                return $member->accessLimitations()->get()
                    ->pluck('groupRole')->map(function ($role) {
                        return $role->users;
                    })
                    ->flatten(1)
                    ->groupBy(function ($v) {
                        return $v->getTable();
                    })
                    ->map(function ($v) {
                        return $v->pluck('id');
                    })->toArray();
            }
        }

        return null;
    }

}
