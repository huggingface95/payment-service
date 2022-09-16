<?php

namespace Database\Seeders;

use App\Models\DepartmentPosition;
use Illuminate\Database\Seeder;

class DepartmentPositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DepartmentPosition::insert([
            [
                'name' => 'Test Department 1',
                'company_id' => 1,
            ], [
                'name' => 'Test Department 2',
                'company_id' => 2,
            ],
        ]);
    }
}
