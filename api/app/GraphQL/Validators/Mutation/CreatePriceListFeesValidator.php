<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\CurrencyMode;
use App\Rules\CurrencyRangeMatches;
use Nuwave\Lighthouse\Validation\Validator;

final class CreatePriceListFeesValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'fees' => [new CurrencyMode, new CurrencyRangeMatches],
        ];
    }
}
