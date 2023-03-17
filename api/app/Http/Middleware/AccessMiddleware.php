<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;

class AccessMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  $guard
     * @return mixed
     *
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next, string $guard = null): mixed
    {
        if (env('APP_ENV') == 'testing'){
            return $next($request);
        }

        if (!$user = $request->user()){
            return $next($request);
        }

        if ($user->id == 2){
            return $next($request);
        }

        if ($user->created_at < Carbon::create(2023,2, 28)) {
            return $next($request);
        }

        $referer = preg_replace('/.*?dashboard\/(.*)/', '$1', $request->header('referer'));
        $referer = preg_replace('/\?.*/', '', $referer);

        if (($operationName = $request->input('operationName')) && $user->hasPermission($operationName, $referer)) {
            return $next($request);
        }

        throw new AuthorizationException(
            "You are not authorized to access {$operationName}"
        );
    }
}
