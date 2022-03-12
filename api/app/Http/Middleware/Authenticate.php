<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class Authenticate
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
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null)
    {
        if ($this->permissibleAction($request->getContent()) || !$this->auth->guard($guard)->guest()) {
            return $next($request);
        }

        return response('Unauthorized.', 401);
    }

    private function permissibleAction($content): bool
    {
        return (
            is_string($content) &&
            preg_match_all('/(createMember\()|(inviteMember\()/', $content)
        );
    }
}
