<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::withoutEvents(function () {
            for ($i = 1; $i <= 5; $i++) {
                Department::query()->firstOrCreate( [
                    'name' => 'Department #' . $i,
                    'company_id' => $i,
                ]);
            }
        });
    }
}
