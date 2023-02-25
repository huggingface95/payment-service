<?php

namespace Database\Seeders;

use App\Models\CompanyModule;
use Illuminate\Database\Seeder;

class CompanyModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyModule::firstOrCreate([
                'company_id' => 1,
                'module_id' => 2,
                'is_active' => true,
            ]);
    }
}
