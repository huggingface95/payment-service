<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\Enums\FeeModeEnum;
use App\Enums\FeeTransferTypeEnum;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantCompany;
use App\Models\Currencies;
use App\Models\Fee;
use App\Models\PriceListFee;
use App\Models\PriceListFeeCurrency;
use App\Models\PriceListPPFeeCurrency;
use App\Models\PriceListQpFeeCurrency;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Support\Collection;

class CommissionService extends AbstractService
{
    /**
     * @throws GraphqlException
     */
    public function makeFee(TransferOutgoing|TransferIncoming $transfer, TransactionDTO $transactionDTO = null): float
    {
        $fee = $this->getAllCommissions($transfer, $transactionDTO);

        $this->createFee($transfer, $fee['fee_amount'], FeeModeEnum::BASE->value)
            ->createFee($transfer, $fee['fee_pp'], FeeModeEnum::PROVIDER->value)
            ->createFee($transfer, $fee['fee_qp'], FeeModeEnum::QUOTEPROVIDER->value)
            ->createFee($transfer, $fee['qp_margin'], FeeModeEnum::MARGIN->value);

        if ($fee['amount_debt'] > 0) {
            $transfer->amount_debt = $fee['amount_debt'];
            $transfer->save();
        }

        return (float) $fee['amount_debt'];
    }

    /**
     * @throws GraphqlException
     */
    public function getAllCommissions(TransferOutgoing|TransferIncoming $transfer, TransactionDTO $transactionDTO = null): array
    {
        $feeAmount = $this->commissionCalculation($transfer, $transactionDTO);
        $feePPAmount = $this->commissionPPCalculation($transfer, $transactionDTO);
        $feeQPAmount = $this->commissionQPCalculation($transfer, $transactionDTO);
        $qpMarginAmount = $this->commissionQPMarginCalculation($transfer, $feeQPAmount);
        $feeTotal = $feeAmount + $feePPAmount + $feeQPAmount;

        $amountDebt = $this->getTransferAmountDebt($transfer, $feeTotal);

        return [
            'fee_amount' => $feeAmount,
            'fee_pp' => $feePPAmount,
            'fee_qp' => $feeQPAmount,
            'fee_total' => $feeTotal,
            'qp_margin' => $qpMarginAmount,
            'amount_debt' => $amountDebt,
        ];
    }

    /**
     * @throws GraphqlException
     */
    private function commissionCalculation(TransferOutgoing|TransferIncoming $transfer, TransactionDTO $transactionDTO = null): float
    {
        $query = PriceListFeeCurrency::query()->where('price_list_fee_id', $transfer->price_list_fee_id);
    
        if ($transfer->operation_type_id == OperationTypeEnum::EXCHANGE->value) {
            $query->join('price_list_fee_destination_currencies', 'price_list_fee_destination_currencies.price_list_fee_currency_id', '=', 'price_list_fee_currency.id')
                ->where('price_list_fee_currency.currency_id', $transactionDTO->currency_src_id)
                ->where('price_list_fee_destination_currencies.currency_id', $transactionDTO->currency_dst_id);
        } else {
            $query->where('currency_id', $transfer->currency_id);
        }
    
        $priceListFees = $query->get();
    
        return $this->calculatePaymentFee($priceListFees, $transfer, FeeModeEnum::BASE, $transactionDTO);
    }

    /**
     * @throws GraphqlException
     */
    private function commissionPPCalculation(TransferOutgoing|TransferIncoming $transfer, TransactionDTO $transactionDTO = null): float
    {
        $priceListFees = PriceListPPFeeCurrency::where('currency_id', $transfer->currency_id)
            ->whereHas('PriceListPPFee', function ($query) use ($transfer) {
                $query->where('payment_system_id', $transfer->payment_system_id)
                    ->where('payment_provider_id', $transfer->payment_provider_id)
                    ->where('operation_type_id', $transfer->operation_type_id);
            })
            ->get();

        return $this->calculatePaymentFee($priceListFees, $transfer, FeeModeEnum::PROVIDER, $transactionDTO);
    }

    /**
     * @throws GraphqlException
     */
    private function commissionQPCalculation(TransferOutgoing|TransferIncoming $transfer, TransactionDTO $transactionDTO = null): float
    {
        if ($transfer->operation_type_id != OperationTypeEnum::EXCHANGE->value) {
            return 0;
        }

        $quoteProviderId = PriceListFee::find($transfer->price_list_fee_id)?->quote_provider_id;

        if (empty($quoteProviderId) && $transfer->operation_type_id == OperationTypeEnum::EXCHANGE->value) {
            return throw new GraphqlException('Quote provider not found. Please setup quote provider');
        }

        $priceListFees = PriceListQpFeeCurrency::where('currency_id', $transactionDTO->currency_src_id)
            ->whereHas('PriceListQpFee', function ($query) use ($quoteProviderId) {
                $query->where('quote_provider_id', $quoteProviderId);
            })
            ->whereHas('feeDestinationCurrency', function ($query) use ($transactionDTO) {
                $query->where('currency_id', $transactionDTO->currency_dst_id);
            })
            ->get();

        return $this->calculatePaymentFee($priceListFees, $transfer, FeeModeEnum::QUOTEPROVIDER, $transactionDTO);
    }

    /**
     * @throws GraphqlException
     */
    private function commissionQPMarginCalculation(TransferOutgoing|TransferIncoming $transfer, float $feeQPAmount): float
    {
        if ($transfer->operation_type_id != OperationTypeEnum::EXCHANGE->value) {
            return 0;
        }

        $quoteProvider = PriceListFee::find($transfer->price_list_fee_id)?->quoteProvider;

        $sum = ($transfer->amount_debt - $feeQPAmount) * ($quoteProvider->margin_commission / 100);

        return $sum;
    }

    /**
     * @throws GraphqlException
     */
    private function calculatePaymentFee($priceListFees, TransferOutgoing|TransferIncoming $transfer, FeeModeEnum $feeMode, TransactionDTO $transactionDTO = null): float
    {
        $paymentFee = 0.0;
        $fees = null;

        foreach ($priceListFees as $listFee) {
            if (! $this->isAllowToApplyCommission($transfer, $listFee, $transactionDTO)) {
                continue;
            }

            $fee = $this->getFee($listFee->fee, $transfer->amount, $transfer->urgency_id);
            if ($fee !== null) {
                $fees += $fee;
            }
        }

        if ($fees === null && $feeMode == FeeModeEnum::BASE) {
            throw new GraphqlException('Fee not found. Please set the fee range equals to 0 to create a fee free transfer');       
        }

        $paymentFee += $fees;

        return (float) $paymentFee;
    }

    private function isAllowToApplyCommission(
        TransferOutgoing|TransferIncoming $transfer,
        PriceListFeeCurrency|PriceListPPFeeCurrency|PriceListQpFeeCurrency $listFee,
        TransactionDTO $transactionDTO = null
    ): bool {
        switch ($transfer->operation_type_id) {
            case OperationTypeEnum::EXCHANGE->value:
                if ($transfer instanceof TransferOutgoing) {
                    if ($listFee instanceof PriceListPPFeeCurrency) {
                        return false;
                    }
                    if ($listFee instanceof PriceListQpFeeCurrency) {
                        return true;
                    }

                    $dstCurrencies = $listFee->feeDestinationCurrency;
                    if ($dstCurrencies->count() > 0) {
                        foreach ($dstCurrencies as $currency) {
                            if ($currency->currency_id == Currencies::ALL_CURRENCIES || $currency->currency_id == $transactionDTO->currency_dst_id) {
                                return true;
                            }
                        }
                    }
                }

                return false;
            case OperationTypeEnum::BETWEEN_ACCOUNT->value:
            case OperationTypeEnum::BETWEEN_USERS->value:
                if ($listFee instanceof PriceListQpFeeCurrency) {
                    return false;
                }
                if ($transfer instanceof TransferOutgoing) {
                    return true;
                }

                return false;
            case OperationTypeEnum::INCOMING_WIRE_TRANSFER->value:
            case OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value:
            case OperationTypeEnum::DEBIT->value:
            case OperationTypeEnum::CREDIT->value:
            case OperationTypeEnum::SCHEDULED_FEE->value:
                if ($listFee instanceof PriceListQpFeeCurrency) {
                    return false;
                }

                return true;
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
        if ($data['mode'] == FeeModeEnum::FIX->toString() || $data['mode'] == FeeModeEnum::BASE->toString()) {
            if (is_array($data['fee'])) {
                if ($urgency == PaymentUrgencyEnum::STANDART->value) {
                    return $data['fee']['standart'];
                } elseif ($urgency == PaymentUrgencyEnum::EXPRESS->value) {
                    return $data['fee']['express'];
                }
            }

            return $data['fee'];
        } elseif ($data['mode'] == FeeModeEnum::PERCENT->toString()) {
            if (is_array($data['percent'])) {
                if ($urgency == PaymentUrgencyEnum::STANDART->value) {
                    return ($data['percent']['standart'] / 100) * $amount;
                } elseif ($urgency == PaymentUrgencyEnum::EXPRESS->value) {
                    return ($data['percent']['express'] / 100) * $amount;
                }
            }

            return ($data['percent'] / 100) * $amount;
        }

        return 0;
    }

    /**
     * @throws GraphqlException
     */
    private function getTransferAmountDebt(TransferOutgoing|TransferIncoming $transfer, float $paymentFee): ?float
    {
        if ($transfer->operation_type_id == OperationTypeEnum::EXCHANGE->value) {
            return $transfer->amount - $paymentFee;
        }

        return match ((int) $transfer->respondent_fees_id) {
            RespondentFeesEnum::CHARGED_TO_CUSTOMER->value => $transfer->amount + $paymentFee,
            RespondentFeesEnum::CHARGED_TO_BENEFICIARY->value => $transfer->amount,
            RespondentFeesEnum::SHARED_FEES->value => $transfer->amount + $paymentFee / 2,

            default => throw new GraphqlException('Unknown respondent fee', 'use'),
        };
    }

    private function createFee(TransferOutgoing|TransferIncoming $transfer, float $paymentFee, int $mode): static
    {
        $transferType = $transfer instanceof TransferOutgoing ? FeeTransferTypeEnum::OUTGOING->toString() : FeeTransferTypeEnum::INCOMING->toString();

        Fee::query()->updateOrCreate(
            [
                'transfer_id' => $transfer->id,
                'transfer_type' => $transferType,
                'fee_type_mode_id' => $mode,
            ],
            [
                'fee' => $paymentFee,
                'fee_type_id' => 1,
                'operation_type_id' => $transfer->operation_type_id,
                'member_id' => null,
                'status_id' => $transfer->status_id,
                'client_id' => 1,
                'client_type' => class_basename(ApplicantCompany::class),
                'account_id' => $transfer->account_id,
                'price_list_fee_id' => $transfer->price_list_fee_id,
                'reason' => $this->getReasonDescription($transfer, $mode),
            ]
        );

        return $this;
    }

    private function getReasonDescription(TransferOutgoing|TransferIncoming $transfer, int $mode): string
    {
        match ($mode) {
            FeeModeEnum::BASE->value => $description = $transfer->paymentSystem?->name,
            FeeModeEnum::PROVIDER->value => $description = $transfer->paymentProvider?->name,
            FeeModeEnum::QUOTEPROVIDER->value => $description = $transfer->priceListFee?->quoteProvider?->name,
            FeeModeEnum::MARGIN->value => $description = '',
            default => $description = '',
        };

        return sprintf(
            'Transfer Fee %s%s',
            FeeModeEnum::from($mode)?->toString(),
            $description ? ': '.$description : ''
        );
    }
}
