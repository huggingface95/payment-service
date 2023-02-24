<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\CurrencyMode;
use App\Rules\CurrencyRangeMatches;
use App\Rules\CurrenciesDestination;
use App\Rules\FeeRanges;
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
            'input.operation_type_id' => ['required', 'int'],
            'input.fees.*.fee' => [new CurrencyMode()],
            'input.fees.*' => [new CurrencyRangeMatches()],
            'input.fee_ranges' => [new FeeRanges(), new CurrenciesDestination($this->arg('input')['operation_type_id'])],
        ];
    }
}
