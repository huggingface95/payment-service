<?php

namespace App\Http\Middleware;

use App\Services\VvService;
use Closure;
use Illuminate\Http\Request;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;

class VvTokenMiddleware
{
    protected VvService $vvService;

    public function __construct(VvService $vvService)
    {
        $this->vvService = $vvService;
    }

    /**
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next, string $guard = null): mixed
    {
        if ($this->vvService->checkToken($request->header('token'))) {
            return $next($request);
        }

        throw new AuthorizationException(
            "This request in unauthorized"
        );
    }
}
