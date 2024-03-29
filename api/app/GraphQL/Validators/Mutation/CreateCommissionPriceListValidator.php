<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\NotEmptyString;
use Nuwave\Lighthouse\Validation\Validator;

final class CreateCommissionPriceListValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', new NotEmptyString],
            'provider_id' => ['required', new NotEmptyString],
            'payment_system_id' => ['required', new NotEmptyString],
            'commission_template_id' => ['required', new NotEmptyString],
            'company_id' => ['required', new NotEmptyString],
            'region_id' => ['filled', new NotEmptyString],
        ];
    }

    /**
     * Return custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'region_id.filled' => 'The region_id field cannot be empty string.',
        ];
    }
}
