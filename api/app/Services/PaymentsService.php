<?php

namespace App\Services;

use App\Enums\FeeModeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\RespondentFeesEnum;
use App\Exceptions\GraphqlException;
use App\Models\Payments;
use Illuminate\Support\Collection;

class PaymentsService extends AbstractService
{

    public function getAllProcessedAmount(Payments $payment): Collection
    {
        return Payments::query()
            ->where('member_id', $payment->member_id)
            ->whereIn('status_id', [PaymentStatusEnum::PENDING->value, PaymentStatusEnum::COMPLETED->value])
            ->get()
            ->push($payment);
    }

    /**
     * Description for getBankAmountRealWithCommission and getAccountAmountRealWithCommission:
     *
     * 1. Withdraw from the sender (CHARGED_TO_CUSTOMER): to the bank Amount, from the balance Amount + Commission
     * 2. Withdraw from the recipient (CHARGED_TO_BENEFICIARY): to the bank Amount - Commission, from the balance the Amount
     * 3. Divide the commission (SHARED_FEES): to the bank Amount - 1/2 commission, from the balance Amount + 1/2 commission
     */
    public function getBankAmountRealWithCommission(Payments $payment, float $paymentFee): ?float
    {
        return match((int) $payment->respondent_fees_id) {
            RespondentFeesEnum::CHARGED_TO_CUSTOMER->value => $payment->amount,
            RespondentFeesEnum::CHARGED_TO_BENEFICIARY->value => $payment->amount - $paymentFee,
            RespondentFeesEnum::SHARED_FEES->value => $payment->amount - $paymentFee / 2,

            default => throw new GraphqlException('Unknown respondent fee', 'use'),
        };
    }

    public function getAccountAmountRealWithCommission(Payments $payment, float $paymentFee): ?float
    {
        return match((int) $payment->respondent_fees_id) {
            RespondentFeesEnum::CHARGED_TO_CUSTOMER->value => $payment->amount + $paymentFee,
            RespondentFeesEnum::CHARGED_TO_BENEFICIARY->value => $payment->amount,
            RespondentFeesEnum::SHARED_FEES->value => $payment->amount + $paymentFee / 2,

            default => throw new GraphqlException('Unknown respondent fee', 'use'),
        };
    }

    public function commissionCalculation(Payments $payment): Payments
    {
        $amountReal = 0;
        $paymentFee = 0;
        $commissionPriceList = $payment->commissionPriceList;
        $priceListFees = $commissionPriceList->fees()->with(['feeType', 'feePeriod', 'operationType', 'fees'])->get();

        /** @var PriceListFee $listFee */
        foreach ($priceListFees as $listFee) {
            if ($listFee->type_id == $payment->fee_type_id
                && $listFee->operation_type_id == $payment->operation_type_id
            ) {
                $paymentFee += $this->getFee(
                    $listFee->fees()->get(),
                    $payment->amount,
                    (int) $payment->currency_id
                );
            }
        }

        $amountReal = $this->getBankAmountRealWithCommission($payment, $paymentFee);

        $payment->fee = $paymentFee;
        $payment->amount_real = $amountReal;

        return $payment;
    }

    public function getFee(Collection $list, $amount, int $currency): ?float
    {
        return $list->map(function ($fee) use ($amount, $currency) {
            if ($currency !== $fee->currency_id) {
                return null;
            }

            $feeItem = $fee->fee->toArray();

            $modeKey = array_search(FeeModeEnum::RANGE->toString(), array_column($feeItem, 'mode'));
            if ($modeKey !== null && $modeKey !== false) {
                return self::getFeeByRangeMode($feeItem, $modeKey, $amount);
            } else {
                return self::getFeeByFixMode($feeItem, $modeKey, $amount);
            }

            return null;
        })->sum();

        return null;
    }

    private static function getFeeByFixMode(array $data, int $modeKey, float $amount): ?float
    {
        return collect($data)->map(function ($fee) use ($amount) {
            return self::getConstantFee($fee, $amount);
        })->sum();

        return null;
    }

    private static function getFeeByRangeMode(array $data, int $modeKey, float $amount): ?float
    {
        $fees = null;
        if ($data[$modeKey]['amount_from'] <= $amount && $amount <= $data[$modeKey]['amount_to']) {
            unset($data[$modeKey]);

            foreach ($data as $fee) {
                $fees += self::getConstantFee($fee, $amount);
            }
        }

        return $fees;
    }

    public static function getConstantFee(array $data, float $amount): ?float
    {
        if ($data['mode'] == FeeModeEnum::FIX->toString()) {
            return $data['fee'];
        } elseif ($data['mode'] == FeeModeEnum::PERCENT->toString()) {
            return ($data['percent'] / 100) * $amount;
        }

        return null;
    }
}
