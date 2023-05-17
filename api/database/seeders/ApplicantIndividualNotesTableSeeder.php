<?php

namespace Database\Seeders;

use App\Models\ApplicantIndividualNotes;
use Illuminate\Database\Seeder;

class ApplicantIndividualNotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            ApplicantIndividualNotes::query()->firstOrCreate(
                [
                    'note' => 'Test Note ' . $i,
                    'applicant_individual_id' => 1,
                    'member_id' => 2,
                ]
            );
        }
    }
}
