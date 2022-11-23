<?php

namespace App\Http\Middleware;

use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;

class AccessByGuardMiddleware
{

    public function __construct(
        protected AuthService $authService
    ) {

    }

    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null): mixed
    {
        $guard = $this->authService->getGuardByClientType($request->client_type);

        if ($guard === 'api_client') {
            config(['auth.providers.members.model' => class_basename(ApplicantIndividual::class)]);
        } else {
            config(['auth.providers.members.model' => class_basename(Members::class)]);
        }

        return $next($request);
    }
}
