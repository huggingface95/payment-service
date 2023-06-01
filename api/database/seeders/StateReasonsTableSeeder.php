<?php

namespace Database\Seeders;

use App\Models\StateReason;
use Illuminate\Database\Seeder;

class StateReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stateReasons = [
            1 => 'Spam',
            2 => 'Not supported',
            3 => 'Fraud',
        ];

        foreach ($stateReasons as $stateReason) {
            StateReason::query()->firstOrCreate([
                'name' => $stateReason,
            ]);
        }
    }
}
