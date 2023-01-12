<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
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
            Module::firstOrCreate([
                'name' => $module,
            ]);
        }
    }
}
