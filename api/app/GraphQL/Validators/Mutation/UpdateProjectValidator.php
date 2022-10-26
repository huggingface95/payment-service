<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\FileOwner;
use Nuwave\Lighthouse\Validation\Validator;

final class UpdateProjectValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'avatar_id' => [new FileOwner('project')],
        ];
    }
}
