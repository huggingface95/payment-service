<?php

namespace App\Http\Middleware;

use App\Models\BaseModel;
use App\Models\Members;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetBaseModelVariablesMiddleware
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null): mixed
    {
        if (Auth::guard('api')->check() || Auth::guard('api_client')->check()) {
            /** @var Members $user */
            $user = Auth::guard('api')->user() ?? Auth::guard('api_client')->user();
            BaseModel::$applicantIds = $this->getApplicantIdsByAuthMember($user);
            BaseModel::$currentCompanyId = $user->company_id == BaseModel::SUPER_COMPANY_ID ? null : $user->company_id;
        }

        return $next($request);
    }

    protected function getApplicantIdsByAuthMember(?Members $member): ?array
    {
        if ($member && $member->accessLimitations()->count()) {
            $ids = $member->accessLimitations()->get()
                ->map(function ($limitation) {
                    $limitation->load('groupRoles.users');

                    return $limitation->groupRoles->pluck('users')->flatten(0)->unique();
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
