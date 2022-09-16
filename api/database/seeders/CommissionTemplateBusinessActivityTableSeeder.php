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
        DB::table('commission_template_business_activity')
            ->insert([
                'commission_template_id' => 1,
                'business_activity_id' => 1,
            ]);
    }
}
