<?php

namespace Database\Seeders;

use App\Models\DepartmentPositionRelation;
use Illuminate\Database\Seeder;

class DepartmentPositionRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DepartmentPositionRelation::insert([
            'department_id'=> 1,
            'position_id' => 1,
        ]);
    }
}
