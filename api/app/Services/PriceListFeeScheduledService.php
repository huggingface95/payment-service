<?php

namespace App\Services;

use App\Enums\FeePeriodEnum;
use App\Models\AccountState;
use App\Models\PriceListFeeScheduled;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PriceListFeeScheduledService extends AbstractService
{
    public function getFromToDates(PriceListFeeScheduled $scheduledFee): array
    {
        $dateExecuted = $scheduledFee->executed_date ?? $scheduledFee->starting_date;
        $dateExecuted = Carbon::parse($dateExecuted)->format('Y-m-d');

        switch ($scheduledFee->priceListFee->period_id) {
            case FeePeriodEnum::DAILY->value:
                $dateFrom = Carbon::now()->subDay();
                break;
            case FeePeriodEnum::WEEKLY->value:
                $dateFrom = Carbon::now()->subWeek();
                break;
            case FeePeriodEnum::MONTHLY->value:
                $dateFrom = Carbon::now()->subMonth();
                break;
            case FeePeriodEnum::YEARLY->value:
                $dateFrom = Carbon::now()->subYear();
                break;
            case FeePeriodEnum::OTHER_SCHEDULE->value:
                $days = $scheduledFee->recurrent_interval;
                $dateFrom = Carbon::now()->subDays($days);
                break;
            default:
                throw new \Exception('Unknown period');
        }

        if ($dateFrom->format('Y-m-d') == $dateExecuted) {
            return [
                'dateFrom' => $dateFrom->format('Y-m-d'),
                'dateTo' => Carbon::now()->format('Y-m-d'),
            ];
        }

        return [];
    }

    public function storeSchdeluedTasksForTodayById(int $feeScheduledId): void
    {
        DB::transaction(function () use ($feeScheduledId) {
            $chunk = 200;

            DB::table('price_list_fee_scheduled')
                ->select([DB::raw('accounts.id as account_id'), 'accounts.currency_id'])
                ->join('price_list_fees', 'price_list_fees.id', '=', 'price_list_fee_scheduled.price_list_fee_id')
                ->join('commission_price_list', 'commission_price_list.id', '=', 'price_list_fees.price_list_id')
                ->join('commission_template', 'commission_template.id', '=', 'commission_price_list.commission_template_id')
                ->join('accounts', 'accounts.commission_template_id', '=', 'commission_template.id')
                ->where('accounts.account_state_id', AccountState::ACTIVE)
                ->where('price_list_fee_scheduled.id', $feeScheduledId)
                ->where('price_list_fee_scheduled.executed_date', '!=', Carbon::now()->format('Y-m-d'))
                ->orderBy('account_id')
                ->chunk($chunk, function ($rows) use ($feeScheduledId) {
                    $arr = json_decode($rows, true);

                    array_walk($arr, function (&$item) use ($feeScheduledId) {
                        $item['date'] = Carbon::now()->format('Y-m-d');
                        $item['price_list_fee_scheduled_id'] = $feeScheduledId;
                    });

                    DB::table('price_list_fee_scheduled_tasks')->upsert($arr, ['price_list_fee_scheduled_id', 'account_id', 'currency_id', 'date']);
                });
        });
    }
}
