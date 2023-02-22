<?php

namespace Database\Seeders;

use App\Enums\ApplicantStatusEnum;
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
        $applicantStatus = ApplicantStatusEnum::cases();

        foreach ($applicantStatus as $status) {
            ApplicantStatus::updateOrCreate([
                'id' => $status->value,
            ], [
                'name' => $status->toString(),
            ]);
        }
    }
}
