<?php

namespace App\Http\Middleware;

use App\Exceptions\GraphqlException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckDeletedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->status() === 200 && $request->path() === 'api') {
            $responseData = json_decode($response->getContent(), true);

            if (isset($responseData['data'])) {
                foreach ($responseData['data'] as $mutationName => $mutationResult) {
                    if ($mutationResult === null && Str::startsWith($mutationName, 'delete')) {
                        throw new GraphqlException('An entry not found', 'internal', 404);
                    }
                }
            }
        }

        return $response;
    }
}
