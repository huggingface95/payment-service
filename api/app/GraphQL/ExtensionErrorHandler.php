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
                'This Email already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'This Email already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'composite_uniqe_commission_template_limit')) {
            return $next(new Error(
                'This threshold already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'This threshold already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_companies_name_unique')) {
            return $next(new Error(
                'This Company Name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'This Company Name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_individual_email_unique')) {
            return $next(new Error(
                'This Email already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'This Email already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'group_role_un')) {
            return $next(new Error(
                'Group with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Group with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'companies_name_unique')) {
            return $next(new Error(
                'Company with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Company with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'payment_system_name_unique')) {
            return $next(new Error(
                'Payment system with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Payment system with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'payment_provider_name_unique')) {
            return $next(new Error(
                'Payment provider with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Payment provider with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_individual_labels_name_unique')) {
            return $next(new Error(
                'Label with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Label with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_company_labels_name_unique')) {
            return $next(new Error(
                'Label with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Label with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_individual_company_relation_name_unique')) {
            return $next(new Error(
                'Relation with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Relation with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_individual_company_position_name_unique')) {
            return $next(new Error(
                'Position with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Position with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'price_list_fees_price_list_id_foreign')) {
            return $next(new Error(
                'Region already in use.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Region already in use.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_company_business_type_name_unique')) {
            return $next(new Error(
                'Business type with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Business type with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'accounts_account_number_unique')) {
            return $next(new Error(
                'Account number already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Account number already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'accounts_account_name_unique')) {
            return $next(new Error(
                'Account name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Account name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'group_role_un')) {
            return $next(new Error(
                'An entry with this  group name already exists',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'An entry with this  group name already exists',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'group_role_name_group_type_id_unique')) {
            return $next(new Error(
                'An entry with this  group name already exists',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'An entry with this  group name already exists',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'department_position_relation_position_id_foreign')) {
            return $next(new Error(
                'Department Position does not exist',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'An entry with this  group name already exists',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_companies_owner_position_id_foreign')) {
            return $next(new Error(
                'Position already in use by other corporate',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Position already in use by other corporate',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_companies_owner_relation_id_foreign')) {
            return $next(new Error(
                'Relation already in use by other corporate',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Relation already in use by other corporate',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'fees_operation_type_id_price_list_fee_id_unique')) {
            return $next(new Error(
                'Fees with this operation has already been added.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Fees with this operation has already been added.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'commission_template_name_unique')) {
            return $next(new Error(
                'CommissionTemplate with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'CommissionTemplate with this name already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'applicant_document_tag_categories')) {
            return $next(new Error(
                'Category couldn\'t be deleted: it contains tags',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => 'Category couldn\'t be deleted: it contains tags',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'companies_email_unique')) {
            return $next(new Error(
                'This email already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'This email already exists.',
                ]
            ));
        }
        if (strpos($error->getMessage(), 'commission_template_company_id_name_unique')) {
            return $next(new Error(
                'Commission Template with this name already exists.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Commission Template with this name already exists.',
                ]
            ));
        }
        if ($error->getMessage() == 'Company not found for this corporate or has been deleted.') {
            return $next(new Error(
                'Company not found for this corporate or has been deleted.',
                // @phpstan-ignore-next-line graphql-php and phpstan disagree with themselves
                $error->getNodes(),
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                new GraphqlException($error->getMessage()),
                [
                    'code' => 409,
                    'systemMessage' => $error->getMessage(), 'Company not found for this corporate or has been deleted.',
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
