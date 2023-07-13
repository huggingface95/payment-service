<?php

namespace App\Models\Traits;

use App\Models\Members;

trait ApplicantIdsForAuthMemberTrait
{
    public static function getApplicantIdsByAuthMember(?Members $member): ?array
    {
        if ($member && $member->accessLimitations()->count()) {
            $ids = $member->accessLimitations()->get()
                ->map(function ($limitation) {
                    $limitation->load('groupRoles');
                    return $limitation->groupRoles->map(function ($groupRole) {
                        return $groupRole->users;
                    })->flatten(0)->unique();
                })
                ->filter(function ($l) {
                    return $l;
                })
                ->flatten(1)
                ->groupBy(function ($v) {
                    return $v->getTable();
                })
                ->map(function ($v) {
                    return $v->pluck('id');
                })
                ->when($member->IsShowOwnerApplicants(), function ($col) use ($member) {
                    return $col->map(function ($records, $type) use ($member) {
                        if ($type == 'applicant_individual') {
                            return $records->intersect($member->accountManagerApplicantIndividuals()->get()->pluck('id'));
                        } elseif ($type == 'applicant_companies') {
                            return $records->intersect($member->accountManagerApplicantCompanies()->get()->pluck('id'));
                        } elseif ($type == 'members') {
                            return $records->intersect($member->accountManagerMembers()->get()->pluck('id'));
                        }

                        return collect();
                    });
                })
                ->toArray();

            return [
                'applicant_individual' => $ids['applicant_individual'] ?? [],
                'applicant_companies' => $ids['applicant_companies'] ?? [],
                'members' => $ids['members'] ?? [],
            ];
        }

        return null;
    }
}
