<?php

namespace Database\Seeders;

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
        $incomingOperationTypeFees = ['Incoming Transfer', 'Between Account', 'Between Users', 'Exchange'];
        $outgoingOperationTypeFees = ['Outgoing Transfer', 'Fee'];
        $feeTypeFees = FeeType::where('name', FeeType::FEES)->first();
        $incomingTransferType = TransferType::where('name', TransferType::INCOMING)->first();
        $outgoingTransferType = TransferType::where('name', TransferType::OUTGOING)->first();
        foreach ($incomingOperationTypeFees as $item) {
            OperationType::firstOrCreate(['name' => $item, 'fee_type_id' => $feeTypeFees->id, 'operation_type_id' => $incomingTransferType->id]);
        }
        foreach ($outgoingOperationTypeFees as $item) {
            OperationType::firstOrCreate(['name' => $item, 'fee_type_id' => $feeTypeFees->id, 'operation_type_id' => $outgoingTransferType->id]);
        }
    }
}
