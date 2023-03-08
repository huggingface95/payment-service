<?php

namespace App\Services;

use App\Enums\FeeModeEnum;
use App\Enums\FeeTransferTypeEnum;
use App\Enums\RespondentFeesEnum;
use App\Exceptions\GraphqlException;
use App\Models\Fee;
use App\Models\PriceListFeeCurrency;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Support\Collection;

class CommissionService extends AbstractService
{

    public function makeFee(TransferOutgoing|TransferIncoming $transfer): float
    {
        $amountDebt = 0;
        $feeAmount = $this->commissionCalculation($transfer);
        $amountDebt = $this->getTransferAmountDebt($transfer, $feeAmount);

        $this->createFee($transfer, $feeAmount);

        return (float) $amountDebt;
    }

    /**
     * @throws GraphqlException
     */
    private function commissionCalculation(TransferOutgoing|TransferIncoming $transfer): float
    {
        $paymentFee = 0;

        $priceListFees = PriceListFeeCurrency::where('price_list_fee_id', $transfer->price_list_fee_id)
            ->where('currency_id', $transfer->currency_id)
            ->get();

        foreach ($priceListFees as $listFee) {
            $paymentFee += $this->getFee($listFee->fee, $transfer->amount);
        }

        return (float) $paymentFee;
    }

    public function getFee(Collection $list, $amount): ?float
    {
        $fee = $list->toArray();
        $modeKey = array_search(FeeModeEnum::RANGE->toString(), array_column($fee, 'mode'));
        if ($modeKey !== null && $modeKey !== false) {
            return self::getFeeByRangeMode($fee, $modeKey, $amount);
        } else {
            return self::getFeeByFixMode($fee, $amount);
        }
    }

    private static function getFeeByFixMode(array $data, float $amount): ?float
    {
        return collect($data)->map(function ($fee) use ($amount) {
            return self::getConstantFee($fee, $amount);
        })->sum();
    }

    private static function getFeeByRangeMode(array $data, int $modeKey, float $amount): ?float
    {
        $fees = null;
        if ((float) $data[$modeKey]['amount_from'] <= $amount && $amount <= (float) $data[$modeKey]['amount_to']) {
            unset($data[$modeKey]);

            foreach ($data as $fee) {
                $fees += self::getConstantFee($fee, $amount);
            }
        }

        return $fees;
    }

    private static function getConstantFee(array $data, float $amount): ?float
    {
        if ($data['mode'] == FeeModeEnum::FIX->toString()) {
            return $data['fee'];
        } elseif ($data['mode'] == FeeModeEnum::PERCENT->toString()) {
            return ($data['percent'] / 100) * $amount;
        }

        return null;
    }

    /**
     * @throws GraphqlException
     */
    private function getTransferAmountDebt(TransferOutgoing|TransferIncoming $transfer, float $paymentFee): ?float
    {
        return match ((int) $transfer->respondent_fees_id) {
            RespondentFeesEnum::CHARGED_TO_CUSTOMER->value => $transfer->amount,
            RespondentFeesEnum::CHARGED_TO_BENEFICIARY->value => $transfer->amount + $paymentFee,
            RespondentFeesEnum::SHARED_FEES->value => $transfer->amount + $paymentFee / 2,

            default => throw new GraphqlException('Unknown respondent fee', 'use'),
        };
    }

    public function createFee(TransferOutgoing|TransferIncoming $transfer, float $paymentFee): void
    {
        $transferType = $transfer instanceof TransferOutgoing ? FeeTransferTypeEnum::OUTGOING->toString() : FeeTransferTypeEnum::INCOMING->toString();

        // TODO: set fee_pp commission
        Fee::updateOrCreate(
            [
                'transfer_id' => $transfer->id,
                'transfer_type' =>  $transferType,
            ],
            [
                'fee' => $paymentFee,
                'fee_pp' => 0,
                'fee_type_id' => 1,
                'operation_type_id' => $transfer->operation_type_id,
                'member_id' => null,
                'status_id' => $transfer->status_id,
                'client_id' => 1,
                'client_type' => class_basename(ApplicantCompany::class),
                'account_id' => $transfer->account_id,
                'price_list_fee_id' => $transfer->price_list_fee_id,
            ]
        );
    }
}
