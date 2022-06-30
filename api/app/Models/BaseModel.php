<?php

namespace App\Models;

use App\Models\Traits\PermissionFilterData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    use PermissionFilterData;

    const DEFAULT_MEMBER_ID = 2;

    protected static function booting()
    {
        self::creating(function ($model) {
            return self::filterByPermissionFilters('creating', $model);
        });
        self::updating(function ($model) {
            return self::filterByPermissionFilters('updating', $model);
        });
        self::deleting(function ($model) {
            return self::filterByPermissionFilters('deleting', $model);
        });

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

    protected static function getApplicantIdsByAuthMember(): ?array
    {
        /** @var Members $member */
        if (($member = Auth::user()) && $member->accessLimitations()->count()) {
            $ids = $member->accessLimitations()->get()
                ->map(function ($limitation) {
                    return $limitation->groupRole->users()->get();
                })
                ->flatten(1)
                ->groupBy(function ($v) {
                    return $v->getTable();
                })
                ->when($member->IsShowOwnerApplicants(), function ($col) use ($member) {
                    return $col->map(function ($records, $type) use ($member) {
                        if ($type == 'applicant_individual') {
                            return $records->pluck('id')->intersect($member->accountManagerApplicantIndividuals()->get()->pluck('id'));
                        } elseif ($type == 'applicant_companies') {
                            return $records->pluck('id')->intersect($member->accountManagerApplicantCompanies()->get()->pluck('id'));
                        }

                        return collect();
                    });
                })
                ->toArray();

            return [
                'applicant_individual' => $ids['applicant_individual'] ?? [],
                'applicant_companies' => $ids['applicant_companies'] ?? [],
            ];
        }

        return null;
    }

    protected static function filterByPermissionFilters($action, Model $model): bool
    {
        /** @var Members $user */
        if ($user = Auth::user()) {
            $allPermissions = $user->getAllPermissions();

            $filters = self::getPermissionFilter(PermissionFilter::EVENT_MODE, $action, $model->getTable(), $model->getAttributes());

            foreach ($filters as $filter) {
                $bindPermissions = $filter->binds->intersect($allPermissions);
                if ($bindPermissions->count() != $filter->binds->count()) {
                    return false;
                }
            }
        }

        return true;
    }
}
