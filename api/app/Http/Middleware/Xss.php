<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Xss
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        array_walk_recursive($input, function (&$input) {
            $input = filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        });

        $request->merge($input);

        return $next($request);
    }
}
