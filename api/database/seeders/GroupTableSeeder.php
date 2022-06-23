<?php

namespace Database\Seeders;

use App\Models\Groups;
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
        Groups::create([
            'id'        => 1,
            'name'     => 'Member',
        ]);

        Groups::create([
            'id'        => 2,
            'name'     => 'Company',
        ]);

        Groups::create([
            'id'        => 3,
            'name'     => 'Individual',
        ]);
    }
}
