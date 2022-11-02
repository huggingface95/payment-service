<?php

namespace Database\Seeders;

use App\Models\ApplicantModules;
use Faker\Factory;
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
        $faker = Factory::create();

        $modules = [
            [
                'name' => $faker->company(),
            ], [
                'name' => $faker->company(),
            ],
        ];

        ApplicantModules::insert($modules);
    }
}
