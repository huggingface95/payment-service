<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\PriceListQpFee;
use App\Services\PriceListFeeService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Mutations\Traits\PriceListFeeTrait;

class PriceListQpFeesMutator
{
    use PriceListFeeTrait;

    public function __construct(
        protected PriceListFeeService $priceListFeeService
    ) {
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args): PriceListQpFee
    {
        if (isset($args['fee_ranges'])) {
            $args['fees'] = $this->priceListFeeService->convertFeeRangesToFees($args);
        }

        $PriceListQpFee = DB::transaction(function () use ($args) {
            $PriceListQpFee = PriceListQpFee::create($args);

            if (isset($args['fees'])) {
                $this->createFeeModes($args, $PriceListQpFee, true);
            }

            return $PriceListQpFee;
        });

        return $PriceListQpFee;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function update($_, array $args): PriceListQpFee
    {
        if (isset($args['fee_ranges'])) {
            $args['fees'] = $this->priceListFeeService->convertFeeRangesToFees($args);
        }

        $PriceListQpFee = PriceListQpFee::find($args['id']);
        if (!$PriceListQpFee) {
            throw new GraphqlException('PriceListQpFee not found', 'use', Response::HTTP_NOT_FOUND);
        }

        DB::transaction(function () use ($PriceListQpFee, $args) {
            $PriceListQpFee->update($args);

            if (isset($args['fees'])) {
                $PriceListQpFee->fees()->delete();

                $this->createFeeModes($args, $PriceListQpFee, true);
            }
        });

        return $PriceListQpFee;
    }
}
