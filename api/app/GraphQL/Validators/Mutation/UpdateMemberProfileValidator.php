<?php

namespace App\GraphQL\Validators\Mutation;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class UpdateMemberProfileValidator extends Validator
{
    public function rules(): array
    {
        return [
            'email' => ['email', Rule::unique('members')->ignore(auth()->user()->id)]
        ];
    }
}
