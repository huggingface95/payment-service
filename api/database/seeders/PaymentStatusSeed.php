<?php

namespace Database\Seeders;

use App\Enums\PaymentStatusEnum;
use App\Models\PaymentStatus;
use App\Models\PaymentSystem;
use Illuminate\Database\Seeder;

class PaymentStatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $operationTypeExchange = [
            PaymentStatusEnum::EXECUTED->value => PaymentStatusEnum::EXECUTED->toString(),
            PaymentStatusEnum::UNSIGNED->value => PaymentStatusEnum::UNSIGNED->toString(),
            PaymentStatusEnum::CANCELED->value => PaymentStatusEnum::CANCELED->toString(),
            PaymentStatusEnum::ERROR->value => PaymentStatusEnum::ERROR->toString(),
        ];

        $operationTypeRest = [
            PaymentStatusEnum::PENDING->value => PaymentStatusEnum::PENDING->toString(),
            PaymentStatusEnum::SENT->value => PaymentStatusEnum::SENT->toString(),
            PaymentStatusEnum::WAITING_EXECUTION_DATE->value => PaymentStatusEnum::WAITING_EXECUTION_DATE->toString(),
        ];

        foreach ($operationTypeExchange as $item => $value) {
            PaymentStatus::firstOrCreate([
                'id' => $item,
                'name' => $value,
                'operation_type' => '{Exchange, Incoming Transfer, Outgoing Transfer}',
            ]);
        }

       foreach ($operationTypeRest as $item => $value) {
            PaymentStatus::firstOrCreate([
                'id' => $item,
                'name' => $value,
                'operation_type' => '{Incoming Transfer, Outgoing Transfer}',
            ]);
        }
    }
}
