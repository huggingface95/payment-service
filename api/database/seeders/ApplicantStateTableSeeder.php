<?php

namespace Database\Seeders;

use App\Models\ApplicantState;
use Illuminate\Database\Seeder;

class ApplicantStateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applicantStates = [
            'Active',
            'Suspended',
            'Blocked',
        ];

        foreach ($applicantStates as $state) {
            ApplicantState::firstOrCreate([
                'name' => $state,
            ]);
        }
    }
}
