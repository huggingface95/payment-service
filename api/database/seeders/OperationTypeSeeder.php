<?php

namespace Database\Seeders;

use App\Enums\OperationTypeEnum;
use App\Enums\TransferTypeEnum;
use App\Models\FeeType;
use App\Models\OperationType;
use App\Models\TransferType;
use Illuminate\Database\Seeder;

class OperationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            TransferTypeEnum::INCOMING_WIRE_TRANSFER->toString() => [OperationTypeEnum::INCOMING_WIRE_TRANSFER->toString()],
            TransferTypeEnum::OUTGOING_WIRE_TRANSFER->toString() => [OperationTypeEnum::OUTGOING_WIRE_TRANSFER->toString()],
            TransferTypeEnum::BETWEEN_ACCOUNT->toString() => [OperationTypeEnum::BETWEEN_ACCOUNT->toString()],
            TransferTypeEnum::BETWEEN_USERS->toString() => [OperationTypeEnum::BETWEEN_USERS->toString()],
            TransferTypeEnum::EXCHANGE->toString() => [OperationTypeEnum::EXCHANGE->toString()],
            TransferTypeEnum::FEE->toString() => [
                OperationTypeEnum::DEBIT->toString(),
                OperationTypeEnum::CREDIT->toString(),
                OperationTypeEnum::SCHEDULED_FEE->toString(),
            ],
        ];

        $feeTypeFee = FeeType::where('name', FeeType::FEES)->first();

        $i = 1;
        foreach ($types as $k => $values) {
            foreach ($values as $v) {
                OperationType::updateOrCreate([
                    'id' => $i++,
                ], [
                    'name' => $v,
                    'fee_type_id' => $feeTypeFee->id,
                    'transfer_type_id' => TransferType::where('name', $k)->first()->id,
                ]);
            }
        }
    }
}
