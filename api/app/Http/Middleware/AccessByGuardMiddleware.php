<?php

namespace App\Http\Middleware;

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
            config(['auth.providers.members.model' => \App\Models\ApplicantIndividual::class]);
        } else {
            config(['auth.providers.members.model' => \App\Models\Members::class]);
        }

        return $next($request);
    }
}
