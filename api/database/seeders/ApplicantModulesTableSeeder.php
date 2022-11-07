<?php

namespace Database\Seeders;

use App\Models\ApplicantModules;
use Illuminate\Database\Seeder;

class ApplicantModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = ['KYC', 'Banking'];

        foreach ($modules as $module) {
            ApplicantModules::firstOrCreate([
                'name' => $module,
            ]);
        }
    }
}
