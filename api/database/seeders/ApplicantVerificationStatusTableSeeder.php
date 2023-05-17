<?php

namespace Database\Seeders;

use App\Enums\ApplicantVerificationStatusEnum;
use App\Models\ApplicantVerificationStatus;
use Illuminate\Database\Seeder;

class ApplicantVerificationStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applicantVerificationStatuses = ApplicantVerificationStatusEnum::cases();

        foreach ($applicantVerificationStatuses as $status) {
            ApplicantVerificationStatus::query()->firstOrCreate([
                'name' => $status->toString(),
            ]);
        }
    }
}
