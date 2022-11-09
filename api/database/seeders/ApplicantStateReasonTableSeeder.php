<?php

namespace Database\Seeders;

use App\Models\ApplicantStateReason;
use Illuminate\Database\Seeder;

class ApplicantStateReasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stateReasons = [
            'Kyc',
            'Documents Expired',
            'Financial Monitoring',
        ];

        foreach ($stateReasons as $reason) {
            ApplicantStateReason::firstOrCreate([
                'name' => $reason,
            ]);
        }
    }
}
