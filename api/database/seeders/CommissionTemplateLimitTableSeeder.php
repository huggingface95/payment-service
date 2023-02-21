<?php

namespace Database\Seeders;

use App\Models\CommissionTemplateLimit;
use Illuminate\Database\Seeder;

class CommissionTemplateLimitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            CommissionTemplateLimit::firstOrCreate(
                [
                    'commission_template_limit_type_id' => 1,
                    'commission_template_limit_transfer_direction_id' => 1,
                    'commission_template_limit_period_id' => 1,
                    'commission_template_limit_action_type_id' => 1,
                    'amount' => 100000,
                    'currency_id' => 1,
                    'period_count' => 1,
                    'commission_template_id' => 1,
                ]
            );
        }
    }
}
