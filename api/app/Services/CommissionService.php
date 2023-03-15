<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\Enums\FeeModeEnum;
use App\Enums\FeeTransferTypeEnum;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Exceptions\GraphqlException;
use App\Models\Currencies;
use App\Models\Fee;
use App\Models\PriceListFeeCurrency;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Support\Collection;

class CommissionService extends AbstractService
{

    public function makeFee(TransferOutgoing|TransferIncoming $transfer, TransactionDTO $transactionDTO = null): float
    {
        $amountDebt = 0;
        $feeAmount = $this->commissionCalculation($transfer, $transactionDTO);
        $amountDebt = $this->getTransferAmountDebt($transfer, $feeAmount);

        $this->createFee($transfer, $feeAmount);

        if ($amountDebt > 0) {
            $transfer->amount_debt = $amountDebt;
            $transfer->save();
        }

        return (float) $amountDebt;
    }

    /**
     * @throws GraphqlException
     */
    private function commissionCalculation(TransferOutgoing|TransferIncoming $transfer, TransactionDTO $transactionDTO = null): float
    {
        $paymentFee = 0;

        $priceListFees = PriceListFeeCurrency::where('price_list_fee_id', $transfer->price_list_fee_id)
            ->where('currency_id', $transfer->currency_id)
            ->get();

        foreach ($priceListFees as $listFee) {
            if (! $this->isAllowToApplyCommission($transfer, $listFee, $transactionDTO)) {
                continue;
            }
           
            $paymentFee += $this->getFee($listFee->fee, $transfer->amount, $transfer->urgency_id);
        }

        return (float) $paymentFee;
    }
    

    private function isAllowToApplyCommission(TransferOutgoing|TransferIncoming $transfer, PriceListFeeCurrency $listFee, TransactionDTO $transactionDTO = null): bool
    {
        switch ($transfer->operation_type_id) {
            case OperationTypeEnum::EXCHANGE->value:
                if ($transfer instanceof TransferOutgoing) {
                    $dstCurrencies = $listFee->feeDestinationCurrency;
                    if ($dstCurrencies->count() > 0) {
                        foreach($dstCurrencies as $currency) {
                            if ($currency->currency_id == Currencies::ALL_CURRENCIES || $currency->currency_id == $transactionDTO->currency_dst_id) {
                                return true;
                            }
                        }
                    }
                }
                return false;
                break;
            case OperationTypeEnum::BETWEEN_ACCOUNT->value:
            case OperationTypeEnum::BETWEEN_USERS->value:
                if ($transfer instanceof TransferOutgoing) {
                    return true;
                }
                return false;
                break;
            case OperationTypeEnum::INCOMING_WIRE_TRANSFER->value:
            case OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value:
            case OperationTypeEnum::DEBIT->value:
            case OperationTypeEnum::CREDIT->value:
            case OperationTypeEnum::SCHEDULED_FEE->value:
                return true;
                break;
            default:
                return false;
        }
    }

    public function getFee(Collection $list, float $amount, int $urgency): ?float
    {
        $fee = $list->toArray();
        $modeKey = array_search(FeeModeEnum::RANGE->toString(), array_column($fee, 'mode'));
        if ($modeKey !== null && $modeKey !== false) {
            return self::getFeeByRangeMode($fee, $modeKey, $amount, $urgency);
        } else {
            return self::getFeeByFixMode($fee, $amount, $urgency);
        }
    }

    private static function getFeeByFixMode(array $data, float $amount, int $urgency): ?float
    {
        return collect($data)->map(function ($fee) use ($amount, $urgency) {
            return self::getConstantFee($fee, $amount, $urgency);
        })->sum();
    }

    private static function getFeeByRangeMode(array $data, int $modeKey, float $amount, int $urgency): ?float
    {
        $fees = null;
        if ((float) $data[$modeKey]['amount_from'] <= $amount && $amount <= (float) $data[$modeKey]['amount_to']) {
            unset($data[$modeKey]);

            foreach ($data as $fee) {
                $fees += self::getConstantFee($fee, $amount, $urgency);
            }
        }

        return $fees;
    }

    private static function getConstantFee(array $data, float $amount, int $urgency): float
    {
        if ($data['mode'] == FeeModeEnum::FIX->toString()) {
            return $data['fee'];
        } elseif ($data['mode'] == FeeModeEnum::PERCENT->toString()) {
            return ($data['percent'] / 100) * $amount;
        } elseif ($data['mode'] == FeeModeEnum::BASE->toString()) {
            if ($urgency == PaymentUrgencyEnum::STANDART->value) {
                return $data['fee']['standart'];
            } elseif ($urgency == PaymentUrgencyEnum::EXPRESS->value) {
                return $data['fee']['express'];
            }

            return 0;
        }

        return 0;
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
