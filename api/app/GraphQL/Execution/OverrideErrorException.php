<?php

namespace App\GraphQL\Execution;

use App\Exceptions\QueryException;
use GraphQL\Error\Error;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Nuwave\Lighthouse\Execution\ErrorHandler;

/**
 * Report errors through the default exception handler configured in Laravel.
 */
class OverrideErrorException implements ErrorHandler
{
    /**
     * @var \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected $exceptionHandler;

    public function __construct(ExceptionHandler $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
    }

    public function __invoke(?Error $error, \Closure $next): ?array
    {
        if (null === $error) {
            return $next(null);
        }

        if (strpos($error, 'invalid input syntax for type bigint')) {
            $error = new Error(
                'Variable of required type must not be null.',
                $error->nodes,
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                null,
                $error->getExtensions(),
            );

            return $next($error);
        }

        $previous = $error->getPrevious();

        if (null !== $previous) {
            if ($previous instanceof \Illuminate\Database\QueryException) {
                $newPreviousError = new QueryException($previous->getMessage(), $previous->getCode(), $previous->getPrevious());
                $error = new Error(
                    $newPreviousError->getMessage(),
                    $error->nodes,
                    $error->getSource(),
                    $error->getPositions(),
                    $error->getPath(),
                    $newPreviousError,
                    $error->getExtensions(),
                );
            }
        }

        return $next($error);
    }
}
