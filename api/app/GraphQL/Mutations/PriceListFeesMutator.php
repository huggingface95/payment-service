<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\PriceListFeeTrait;
use App\Models\CommissionPriceList;
use App\Models\PaymentSystem;
use App\Models\PriceListFee;
use App\Services\PriceListFeeService;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PriceListFeesMutator
{
    use PriceListFeeTrait;

    public function __construct(protected PriceListFeeService $priceListFeeService)
    {
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args): PriceListFee
    {
        $internalPriceListExists = CommissionPriceList::where('id', $args['price_list_id'])
            ->whereHas('paymentSystem', function ($query) {
                $query->where('name', PaymentSystem::NAME_INTERNAL);
            })
            ->exists();

        if ($internalPriceListExists && $args['price_list_id'] && $args['operation_type_id']) {
            $priceListFeeExists = PriceListFee::where('price_list_id', $args['price_list_id'])
                ->where('operation_type_id', $args['operation_type_id'])
                ->where('operation_type_id', '!=', OperationTypeEnum::SCHEDULED_FEE->value)
                ->exists();
            if ($priceListFeeExists) {
                throw new GraphqlException('Only one PriceListFee is allowed for the PriceList with Internal provider and operation type ' . OperationTypeEnum::tryFrom($args['operation_type_id'])->toString(), 'use');
            }
        }

        if (isset($args['fee_ranges'])) {
            $args['fees'] = $this->priceListFeeService->convertFeeRangesToFees($args);
        }

        $priceListFee = DB::transaction(function () use ($args) {
            $priceListFee = PriceListFee::create($args);

            if (isset($args['fees'])) {
                $this->createFeeModes($args, $priceListFee);
            }

            if (isset($args['scheduled']) && !empty($args['scheduled'])) {
                if (empty($args['scheduled']['starting_date'])) {
                    $args['scheduled']['starting_date'] = Carbon::now();
                }
                if (! empty($args['scheduled']['end_date']) && Carbon::parse($args['scheduled']['end_date'])->lt($args['scheduled']['starting_date'])) {
                    throw new GraphqlException('end_date cannot be earlier than starting_date', 'use');
                }

                $existingScheduled = $priceListFee->feeScheduled()
                    ->where('recurrent_interval', $args['recurrent_interval'])
                    ->where('price_list_fee_id', $args['price_list_fee_id'])
                    ->first();

                if ($existingScheduled) {
                    throw new GraphqlException('Scheduled already exists for the given parameters', 'use');
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
        if (! $priceListFee) {
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
}
