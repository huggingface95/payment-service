<?php

namespace Database\Seeders;

use App\Models\ApplicantCompanyModules;
use Illuminate\Database\Seeder;

class ApplicantCompanyModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            ApplicantCompanyModules::insert(
                [
                    'applicant_company_id' => $i,
                    'module_id' => 2,
                    'is_active' => true,
                ]
            );
        }
    }
}
