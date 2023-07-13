<?php

namespace App\Http\Middleware;

use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\Members;
use App\Models\Traits\ApplicantIdsForAuthMemberTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetBaseModelVariablesMiddleware
{
    use ApplicantIdsForAuthMemberTrait;
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null): mixed
    {
        if (Auth::guard('api')->check() || Auth::guard('api_client')->check()) {
            /** @var Members|ApplicantIndividual $user */
            $user = Auth::guard('api')->user() ?? Auth::guard('api_client')->user();
            if ($user instanceof Members) {
                BaseModel::$applicantIds = $this->getApplicantIdsByAuthMember($user);
            }
            BaseModel::$currentCompanyId = $user->company_id == BaseModel::SUPER_COMPANY_ID ? null : $user->company_id;
        }

        return $next($request);
    }


}
