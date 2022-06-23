<?php

namespace Database\Seeders;

use App\Models\AccountStates;
use Illuminate\Database\Seeder;

class AccountStatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountStates::create([
            'id'        => 1,
            'name'     => 'Active',
        ]);

        AccountStates::create([
            'id'        => 2,
            'name'     => 'Suspended',
        ]);

        AccountStates::create([
            'id'        => 3,
            'name'     => 'Blocked',
        ]);

        AccountStates::create([
            'id'        => 4,
            'name'     => 'Pending',
        ]);

        AccountStates::create([
            'id'        => 5,
            'name'     => 'Closed',
        ]);
    }
}
