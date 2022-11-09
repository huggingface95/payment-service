<?php

namespace Database\Seeders;

use App\Models\ApplicantStatus;
use Illuminate\Database\Seeder;

class ApplicantStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applicantStatus = [
            'Requested',
            'Declined',
            'Approved',
            'Pending',
        ];

        foreach ($applicantStatus as $status) {
            ApplicantStatus::firstOrCreate([
                'name' => $status,
            ]);
        }
    }
}
