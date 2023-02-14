<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\PriceListFee;
use App\Models\PriceListFeeDestinationCurrency;
use App\Services\PriceListFeeService;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PriceListFeesMutator
{
    public function __construct(protected PriceListFeeService $priceListFeeService)
    {
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args): PriceListFee
    {
        if (isset($args['fee_ranges'])) {
            $args['fees'] = $this->priceListFeeService->convertFeeRangesToFees($args);
        }

        $priceListFee = DB::transaction(function () use ($args) {
            $priceListFee = PriceListFee::create($args);

            if (isset($args['fees'])) {
                $this->createFeeModes($args, $priceListFee);
            }

            if (isset($args['scheduled'])) {
                if (empty($args['scheduled']['starting_date'])) {
                    $args['scheduled']['starting_date'] = Carbon::now();
                }
                if (!empty($args['scheduled']['end_date']) && Carbon::parse($args['scheduled']['end_date'])->lt($args['scheduled']['starting_date'])) {
                    throw new GraphqlException('end_date cannot be earlier than starting_date', 'use');
                }

                $priceListFee->feeScheduled()->create($args['scheduled']);
            }

            return $priceListFee;
        });

        return $priceListFee;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function update($_, array $args): PriceListFee
    {
        if (isset($args['fee_ranges'])) {
            $args['fees'] = $this->priceListFeeService->convertFeeRangesToFees($args);
        }

        $priceListFee = PriceListFee::find($args['id']);
        if (!$priceListFee) {
            throw new GraphqlException('PriceListFee not found', 'use', Response::HTTP_NOT_FOUND);
        }
        
        DB::transaction(function () use ($priceListFee, $args) {
            $priceListFee->update($args);

            if (isset($args['commission_price_list'][0])) {
                $field = $args['commission_price_list'][0];

                $priceListFee->priceList()->update([
                    'provider_id' => $field['payment_provider_id'],
                    'payment_system_id' => $field['payment_system_id'],
                    'commission_template_id' => $field['commission_template_id'],
                    'company_id' => $field['company_id'],
                ]);
            }

            if (isset($args['fees'])) {
                $priceListFee->fees()->delete();

                $this->createFeeModes($args, $priceListFee);
            }

            if (isset($args['scheduled'])) {
                $priceListFee->feeScheduled()->delete();
                $priceListFee->feeScheduled()->create($args['scheduled']);
            }
        });

        return $priceListFee;
    }

    private function createFeeModes(array $args, PriceListFee $priceListFee): void
    {
        $currencies = $args['fees'];
        foreach ($currencies as $currency) {
            foreach ($currency['fee'] as $fees) {
                $priceListFeeCurrency = $priceListFee->fees()->create([
                    'price_list_fee_id' => $priceListFee->id,
                    'currency_id' => $currency['currency_id'],
                    'fee' => $fees,
                ]);

                if ($args['operation_type_id'] == OperationTypeEnum::EXCHANGE->value) {
                    foreach ($currency['currencies_destination'] as $currency) {
                        PriceListFeeDestinationCurrency::create([
                            'price_list_fee_currency_id' => $priceListFeeCurrency->id,
                            'currency_id' => $currency,
                        ]);
                    }
                }
            }
        }
    }
}
