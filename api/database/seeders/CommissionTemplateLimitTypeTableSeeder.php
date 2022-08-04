<?php

namespace Database\Seeders;

use App\Models\CommissionTemplateLimitType;
use Illuminate\Database\Seeder;

class CommissionTemplateLimitTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
            1 => CommissionTemplateLimitType::ALL,
            2 => CommissionTemplateLimitType::TRANSACTION_AMOUNT,
            3 => CommissionTemplateLimitType::TRANSACTION_COUNT,
            4 => CommissionTemplateLimitType::TRANSFER_COUNT,
        ] as $id => $name) {
            /** @var CommissionTemplateLimitType $commissionTemplateType */
            if ($commissionTemplateType = CommissionTemplateLimitType::find($id)) {
                $commissionTemplateType->update(['name' => $name]);
            } else {
                CommissionTemplateLimitType::create(['id' => $id, 'name' => $name]);
            }
        }
    }
}
