<?php

namespace App\GraphQL\Queries;

use App\Models\EmailTemplate;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class EmailTemplateQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $templates = EmailTemplate::all();
        foreach ($templates as $key => $template) {
            if ($template->type == EmailTemplate::CLIENT && $template->service_type == EmailTemplate::ADMIN) {
                unset($templates[$key]);
            }
        }

        return $templates;
    }
}
