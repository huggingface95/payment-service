<?php

namespace Database\Seeders;

use App\Models\GroupType;
use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupType::create([
            'id'        => 1,
            'name'     => 'Member',
        ]);

        GroupType::create([
            'id'        => 2,
            'name'     => 'Company',
        ]);

        GroupType::create([
            'id'        => 3,
            'name'     => 'Individual',
        ]);
    }
}
