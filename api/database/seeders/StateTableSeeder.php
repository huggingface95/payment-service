<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            1 => 'Inactive',
            2 => 'Active',
        ];

        foreach ($states as $state) {
            State::query()->firstOrCreate([
                'name' => $state,
            ]);
        }
    }
}
