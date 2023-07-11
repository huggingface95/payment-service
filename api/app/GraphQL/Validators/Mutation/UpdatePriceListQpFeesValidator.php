<?php

namespace App\GraphQL\Validators\Mutation;

use App\Enums\OperationTypeEnum;
use App\Rules\CurrenciesDestination;
use App\Rules\CurrencyMode;
use App\Rules\CurrencyRangeMatches;
use App\Rules\FeeRanges;
use App\Rules\RequiredCurrenciesDestination;
use Nuwave\Lighthouse\Validation\Validator;

final class UpdatePriceListQpFeesValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'input.fees.*.fee' => [new CurrencyMode()],
            'input.fees.*' => [new CurrencyRangeMatches()],
            'input.fee_ranges' => [
                new FeeRanges(),
                new CurrenciesDestination(OperationTypeEnum::EXCHANGE->value),
                new RequiredCurrenciesDestination(OperationTypeEnum::EXCHANGE->value),
            ],
        ];
    }
}
