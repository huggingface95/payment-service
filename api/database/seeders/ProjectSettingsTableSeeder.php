<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectSettings;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProjectSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            ProjectSettings::query()->firstOrCreate([
                'project_id' => $i,
                'group_type_id' => $faker->randomElement([1, 3]),
                'group_role_id' => $faker->randomElement([1, 3]),
                'commission_template_id' => $i,
                'payment_provider_id' => $i,
                'iban_provider_id' => $i,
                'applicant_type' => $faker->randomElement([class_basename(ApplicantIndividual::class), class_basename(ApplicantCompany::class)]),
            ]);
        }
    }
}
