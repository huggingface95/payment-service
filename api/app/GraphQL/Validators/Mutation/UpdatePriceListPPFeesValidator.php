<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\CurrencyMode;
use App\Rules\CurrencyRangeMatches;
use App\Rules\FeeRanges;
use App\Rules\FeeValueAsObject;
use Nuwave\Lighthouse\Validation\Validator;

final class UpdatePriceListPPFeesValidator extends Validator
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
            'input.fee_ranges' => [new FeeRanges(), new FeeValueAsObject()],
        ];
    }
}
