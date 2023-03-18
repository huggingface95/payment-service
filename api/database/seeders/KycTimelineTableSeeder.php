<?php

namespace Database\Seeders;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\KycTimeline;
use Faker\Factory;
use Illuminate\Database\Seeder;

class KycTimelineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 4; $i++) {
            KycTimeline::firstOrCreate(
                [
                    'creator_id' => $i,
                ],
                [
                    'os' => $faker->randomElement(['Windows', 'iOS', 'macOS']),
                    'browser' => $faker->randomElement(['Opera', 'Chrome', 'Firefox']),
                    'ip' => $faker->ipv4,
                    'action' => 'test action',
                    'tag' => 'KYC',
                    'action_type' => $faker->randomElement(['document_upload', 'document_state', 'verification', 'email']),
                    'document_id' => 1,
                    'company_id' => $i,
                    'applicant_id' => $i,
                    'applicant_type' => $i % 2 ? class_basename(ApplicantIndividual::class) : class_basename(ApplicantCompany::class),
                    'created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
