<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionTemplateBusinessActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = DB::table('commission_template_business_activity')->where([
            'commission_template_id' => 1,
            'business_activity_id' => 1,
        ])->first();
        
        if (!$row) {
            DB::table('commission_template_business_activity')
                ->insert([
                    'commission_template_id' => 1,
                    'business_activity_id' => 1,
                ]);
        }
    }
}
