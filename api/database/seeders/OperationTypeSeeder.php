<?php

namespace Database\Seeders;

use App\Models\FeeType;
use App\Models\OperationType;
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
        $operationTypeFees = ['Incoming Transfer', 'Outgoing Transfer', 'Deposit', 'System Deposit', 'Currency Conversion'];
        $operationTypeServiceFee = ['Service', 'Additional Account', 'Annual Balance'];
        $feeTypeFees = FeeType::where('name', FeeType::FEES)->first();
        $feeTypeServiceFee = FeeType::where('name', FeeType::SERVICE_FEE)->first();
        foreach ($operationTypeFees as $item) {
            OperationType::firstOrCreate(['name'=>$item, 'fee_type_id'=>$feeTypeFees->id]);
        }
        foreach ($operationTypeServiceFee as $item) {
            OperationType::firstOrCreate(['name'=>$item, 'fee_type_id'=>$feeTypeServiceFee->id]);
        }
    }
}
