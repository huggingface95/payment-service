<?php

namespace Database\Seeders;

use App\Models\ApplicantCompany;
use App\Models\ApplicantDocument;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Seeder;

class ApplicantDocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = [
            1 => [
                'document_type_id' => 1,
                'document_state_id' => 1,
                'file_id' => 1,
                'applicant_id' => 1,
                'applicant_type' => class_basename(ApplicantIndividual::class),
                'company_id' => 1,
            ],
            [
                'document_type_id' => 2,
                'document_state_id' => 2,
                'file_id' => 1,
                'applicant_id' => 1,
                'applicant_type' => class_basename(ApplicantIndividual::class),
                'company_id' => 1,
            ],
            [
                'document_type_id' => 1,
                'document_state_id' => 2,
                'file_id' => 1,
                'applicant_id' => 2,
                'applicant_type' => class_basename(ApplicantCompany::class),
                'company_id' => 1,
            ],
        ];

        foreach ($documents as $id => $document) {
            ApplicantDocument::firstOrCreate(['id' => $id], $document);
        }
    }
}
