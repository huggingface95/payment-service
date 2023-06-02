<?php

namespace Database\Seeders;

use App\Models\Employee;
use Faker\Factory;
use Illuminate\Database\Seeder;

class EmploeesTableSeeder extends Seeder
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
            Employee::query()->firstOrCreate([
                'employees_number' => $faker->randomNumber(),
            ]);
        }
    }
}
