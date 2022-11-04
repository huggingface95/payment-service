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
        $limits = [
            1 => CommissionTemplateLimitType::ALL,
            2 => CommissionTemplateLimitType::TRANSACTION_AMOUNT,
            3 => CommissionTemplateLimitType::TRANSACTION_COUNT,
            4 => CommissionTemplateLimitType::TRANSFER_COUNT,
        ];

        foreach ($limits as $id => $name) {
            $limit = CommissionTemplateLimitType::find($id);
            if ($limit) {
                if ($limit->name != $name) {
                    $limit->name = $name;
                    $limit->save();
                }
            } else {
                CommissionTemplateLimitType::firstOrCreate([
                    'id' => $id,
                    'name' => $name,
                ]);
            }
        }
    }
}
