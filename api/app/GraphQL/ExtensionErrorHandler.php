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
        if (strpos($error->getMessage(), 'applicant_companies_email_unique')) {
            return $next(new Error(
                'This Email already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'This Email already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_companies_name_unique')) {
            return $next(new Error(
                'This Company Name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'This Company Name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_individual_email_unique')) {
            return $next(new Error(
                'This Email already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'This Email already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'group_role_un')) {
            return $next(new Error(
                'Group with this name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Group with this name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'companies_name_unique')) {
            return $next(new Error(
                'Company with this name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Company with this name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'payment_system_name_unique')) {
            return $next(new Error(
                'Payment system with this name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Payment system with this name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'payment_provider_name_unique')) {
            return $next(new Error(
                'Payment provider with this name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Payment provider with this name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_individual_labels_name_unique')) {
            return $next(new Error(
                'Label with this name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Label with this name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_company_labels_name_unique')) {
            return $next(new Error(
                'Label with this name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Label with this name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_individual_company_relation_name_unique')) {
            return $next(new Error(
                'Relation with this name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Relation with this name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_individual_company_position_name_unique')) {
            return $next(new Error(
                'Position with this name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Position with this name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_company_business_type_name_unique')) {
            return $next(new Error(
                'Business type with this name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Business type with this name already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'accounts_account_number_unique')) {
            return $next(new Error(
                'Account number already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Account number already exist.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'accounts_account_name_unique')) {
            return $next(new Error(
                'Account name already exist.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Account name already exist.',
                ]
            ));
        }

        if (strpos($error->getMessage(), 'group_role_un')) {
            return $next(new Error(
                'An entry with this  group name already exist',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'An entry with this  group name already exist',
                ]
            ));
        }

        if (strpos($error->getMessage(), 'group_role_name_group_type_id_unique')) {
            return $next(new Error(
                'An entry with this  group name already exist',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'An entry with this  group name already exist',
                ]
            ));
        }
        //$underlyingException = $error->getPrevious();
        if (strpos($error->getMessage(), 'duplicate')) {
            return $next(new Error(
                'An entry with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(),
                ]
            ));
        }
        if (strpos($error->getMessage(), 'NOT FOUND IN THIS ')) {
            return $next(new Error(
                strstr($error->getMessage(), ' IN THIS', true),
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 500,
                    'systemMessage' => $error->getMessage(),
                ]
            ));
        }
        if (strpos($error->getMessage(), 'non-nullable')) {
            return $next(new Error(
                'Internal server error',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 500,
                    'systemMessage' => $error->getMessage(),
                ]
            ));
        }
        if (strpos($error->getMessage(), 'null')) {
            return $next(new Error(
                'An entry with this id does not exist',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 404,
                    'systemMessage' => $error->getMessage(),
                ]
            ));
        }
        if ($error->getCategory() == 'not found') {
            return $next(new Error(
                'An entry with this id does not exist',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 404,
                    'systemMessage' => $error->getMessage(),
                ]
            ));
        }
        if ($error->getCategory() == 'internal') {
            return $next(new Error(
                (env('APP_ENV') !== 'local') ? 'Server internal error' : $error->getMessage(),
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 500,
                    'systemMessage' => $error->getMessage(),
                ]
            ));
        }

        if ($error->getCategory() == 'graphql') {
            Log::error($error);
//            preg_match('/argument (.*?) of type/', $error->getMessage(), $match);

            return $next(new Error(
                $error->getMessage(),
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage(), 'request'),
                [
                    'code' => 400,
                    //'systemMessage' => $error->getMessage()
                ]
            ));
        }

        // Keep the pipeline going, last step formats the error into an array
        return $next($error);
    }
}
