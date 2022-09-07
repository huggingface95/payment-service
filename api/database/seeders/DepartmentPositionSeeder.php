<?php

namespace Database\Seeders;

use App\Models\DepartmentPosition;
use Illuminate\Database\Seeder;

class DepartmentPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DepartmentPosition::insert([
            'name'=> 'Test',
            'company_id' => 1,
        ]);
    }
}
