<?php

namespace Database\Seeders;

use App\Enums\PaymentStatusEnum;
use App\Models\PaymentStatus;
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
        $operationTypes = [
            PaymentStatusEnum::PENDING->toString() => '{Incoming Transfer, Outgoing Transfer}',
            PaymentStatusEnum::SENT->toString() => '{Incoming Transfer, Outgoing Transfer}',
            PaymentStatusEnum::ERROR->toString() => '{Exchange, Incoming Transfer, Outgoing Transfer}',
            PaymentStatusEnum::CANCELED->toString() => '{Exchange, Incoming Transfer, Outgoing Transfer}',
            PaymentStatusEnum::UNSIGNED->toString() => '{Exchange, Incoming Transfer, Outgoing Transfer}',
            PaymentStatusEnum::WAITING_EXECUTION_DATE->toString() => '{Incoming Transfer, Outgoing Transfer}',
            PaymentStatusEnum::EXECUTED->toString() => '{Exchange, Incoming Transfer, Outgoing Transfer}',
            PaymentStatusEnum::REFUND->toString() => '{Incoming Transfer, Outgoing Transfer}',
        ];

        foreach ($operationTypes as $name => $operationType) {
            PaymentStatus::query()->firstOrCreate([
                'name' => $name,
                'operation_type' => $operationType,
            ]);
        }
    }
}
