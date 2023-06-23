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
        $employeesNumbers = [
            1 => '1-5',
            2 => '11-50',
            3 => '51-100',
            4 => '101-1000',
            5 => '>1000',
        ];

        foreach ($employeesNumbers as $id => $employeesNumber) {
            Employee::query()->firstOrCreate(
                ['id' => $id],
                ['employees_number' => $employeesNumber]
            );
        }
    }
}
