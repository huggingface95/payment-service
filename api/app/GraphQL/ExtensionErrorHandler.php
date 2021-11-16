<?php
namespace App\GraphQL;

use App\Exceptions\GraphqlException;
use Closure;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\ErrorHandler;

class ExtensionErrorHandler implements ErrorHandler
{
    public function __invoke(?Error $error, Closure $next): ?array
    {
        if ($error === null) {
            return $next(null);
        }
        //$underlyingException = $error->getPrevious();
        if (strpos($error->getMessage(),'duplicate')) {
            return $next(new Error(
                'An entry with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409
                ]
            ));
        }
        if ($error->getCategory() == 'internal') {
            return $next(new Error(
                (env('APP_ENV')!=='local') ? 'Server internal error' : $error->getMessage(),
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 500
                ]
            ));
        }

        if ($error->getCategory() == 'graphql') {
            Log::error($error);
            return $next(new Error(
                (env('APP_ENV')!=='local') ? 'Bad request' : $error->getMessage(),
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage(),'request'),
                [
                    'code' => 400
                ]
            ));
        }


        // Keep the pipeline going, last step formats the error into an array
        return $next($error);
    }
}
