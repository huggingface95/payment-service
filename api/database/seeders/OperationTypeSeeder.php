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

        $feeTypeFeeId = FeeType::where('name', FeeType::FEES)->first()->id;
        $serviceFeeTypeId = FeeType::where('name', FeeType::SERVICE_FEE)->first()->id;

        foreach ($types as $k => $values) {
            foreach ($values as $v) {
                OperationType::query()->updateOrCreate([
                    'name' => $v,
                    'fee_type_id' => $v === OperationTypeEnum::SCHEDULED_FEE->toString() ? $serviceFeeTypeId : $feeTypeFeeId,
                    'transfer_type_id' => TransferType::where('name', $k)->first()->id,
                ]);
            }
        }
    }
}
