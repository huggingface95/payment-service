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
            'Document Requested',
            'Pending',
            'Processing',
            'Check Complited',
            'Verified',
            'Rejected',
            'Resubmission Requested',
            'Requires Action',
            'Prechecked',
        ];

        foreach ($applicantStatus as $status) {
            ApplicantStatus::firstOrCreate([
                'name' => $status,
            ]);
        }
    }
}
