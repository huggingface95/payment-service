<?php

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

class CurrenciesAndRegionsValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'currency_id' => ['required', 'array', 'min:1'],
            'currency_id.*' => ['required', 'int'],
        ];
    }

    public function messages(): array
    {
        return [
            'currency_id.required' => 'The currency_id field cannot be empty array.',
            'currency_id.*.*' => 'The currency_id field value must be type ID',
        ];
    }
}
