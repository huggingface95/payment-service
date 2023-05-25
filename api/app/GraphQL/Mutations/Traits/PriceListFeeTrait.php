<?php

namespace App\GraphQL\Mutations\Traits;

use App\Enums\OperationTypeEnum;
use App\Models\PriceListFee;
use App\Models\PriceListPPFee;
use App\Models\PriceListQpFee;
use Illuminate\Support\Str;

trait PriceListFeeTrait
{
    private function createFeeModes(array $args, PriceListFee|PriceListPPFee|PriceListQpFee $priceListFee, bool $isExchange = false): void
    {
        $modelBase = class_basename($priceListFee);
        $fieldBase = Str::snake($modelBase);
        $currencies = $args['fees'];

        foreach ($currencies as $currency) {
            foreach ($currency['fee'] as $fees) {
                $priceListFeeCurrency = $priceListFee->fees()->create([
                    $fieldBase.'_id' => $priceListFee->id,
                    'currency_id' => $currency['currency_id'],
                    'fee' => $fees,
                ]);

                if ((isset($args['operation_type_id']) && $args['operation_type_id'] == OperationTypeEnum::EXCHANGE->value) || $isExchange) {
                    $model = 'App\\Models\\'.$modelBase.'DestinationCurrency';

                    foreach ($currency['currencies_destination'] as $cId) {
                        $model::create([
                            $fieldBase.'_currency_id' => $priceListFeeCurrency->id,
                            'currency_id' => $cId,
                        ]);
                    }
                }
            }
        }
    }
}
