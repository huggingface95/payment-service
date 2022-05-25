<?php

namespace App\GraphQL\Mutations;

use App\Models\BaseModel;
use App\Models\EmailTemplate;

class EmailTemplateMutator
{
    public function create($root, array $args)
    {
        $args['member_id'] = BaseModel::DEFAULT_MEMBER_ID;

        return EmailTemplate::create($args);
    }
}
