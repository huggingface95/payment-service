<?php

namespace Database\Seeders;

use App\Models\Project;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            Project::firstOrCreate([
                'id' => $i,
            ], [
                'name' => $faker->company(),
                'company_id' => $i,
                'module_id' => $faker->randomElement([1, 2]),
            ]);
        }
    }
}
