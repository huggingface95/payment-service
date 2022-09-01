<?php

namespace Database\Seeders;

use App\Enums\GuardEnum;
use App\Models\DepartmentPosition;
use App\Models\DepartmentPositionRelation;
use App\Models\Departments;
use App\Models\Role;
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
