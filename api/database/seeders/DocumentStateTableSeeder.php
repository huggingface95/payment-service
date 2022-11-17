<?php

namespace Database\Seeders;

use App\Models\DocumentState;
use Illuminate\Database\Seeder;

class DocumentStateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            'Pending',
            'Processing',
            'Confirmed',
            'Declined',
        ];

        foreach ($states as $state) {
            DocumentState::firstOrCreate(['name' => $state]);
        }
    }
}
