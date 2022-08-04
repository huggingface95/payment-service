<?php

namespace App\Http\Middleware;

use App\Models\BaseModel;
use App\Models\Members;
use Closure;
use Illuminate\Http\Request;

class SetBaseModelVariablesMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null): mixed
    {
        BaseModel::$applicantIds = $this->getApplicantIdsByAuthMember($request->user());

        return $next($request);
    }


    protected function getApplicantIdsByAuthMember(Members $member): ?array
    {
        if ($member->accessLimitations()->count()) {
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
}
