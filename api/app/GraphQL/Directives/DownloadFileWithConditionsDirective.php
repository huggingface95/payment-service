<?php

namespace App\GraphQL\Directives;

use App\Services\ExportService;
use GraphQL\Error\Error;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class DownloadFileWithConditionsDirective extends BaseDirective implements FieldResolver
{
    public function __construct(protected ExportService $exportService)
    {
    }

    public static function definition(): string
    {
        return /** @lang GraphQL */<<<'GRAPHQL'
"""
Find a model based on the arguments provided.
"""
directive @downloadFileWithConditions(
    """
    Specify the class name of the model to use.
    This is only needed when the default model detection does not work.
    """
    model: String
) on FIELD_DEFINITION
GRAPHQL;
    }

    public function resolveField(FieldValue $fieldValue): FieldValue
    {
        $fieldValue->setResolver(function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array {
            if (!$this->directiveHasArgument('model')) {
                throw new Error('You must provide a model argument to the @downloadFileWithConditions directive.');
            }

            $model = 'App\\Models\\' . $this->directiveArgValue('model');

            $results = $resolveInfo->enhanceBuilder(
                $model::query(),
                $this->directiveArgValue('scopes', [])
            )->get();

            $raw = $this->exportService->exportTransfersList($this->directiveArgValue('model'), $results, $args['type']);

            return [
                'base64' => base64_encode($raw),
            ];
        });

        return $fieldValue;
    }
}
