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
        $incomingOperationTypeFees = ['Between Account', 'Between Users', 'Exchange'];
        $outgoingOperationTypeFees = ['Outgoing Transfer', 'Fee'];
        $feeTypeFees = FeeType::where('name', FeeType::FEES)->first();
        OperationType::firstOrCreate(['name' => 'Incoming Transfer', 'fee_type_id' => $feeTypeFees->id, 'transfer_type' => '{Incoming}']);
        foreach ($incomingOperationTypeFees as $item) {
            OperationType:: firstOrCreate(['name' => $item, 'fee_type_id' => $feeTypeFees->id, 'transfer_type' => '{Incoming, Outgoing}']);
        }
        foreach ($outgoingOperationTypeFees as $item) {
            OperationType::firstOrCreate(['name' => $item, 'fee_type_id' => $feeTypeFees->id, 'transfer_type' => '{Outgoing}']);
        }
    }
}
