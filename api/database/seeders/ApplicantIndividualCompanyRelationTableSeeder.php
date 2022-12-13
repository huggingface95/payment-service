<?php

namespace Database\Seeders;

use App\Models\ApplicantIndividualCompanyRelation;
use Illuminate\Database\Seeder;

class ApplicantIndividualCompanyRelationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicantIndividualCompanyRelation::truncate();

        $companyRelations = [
            ['name'=>'Director', 'company_id' => 1],
            ['name'=>'Shareholder', 'company_id' => 1],
        ];

        foreach ($companyRelations as $companyRelation) {
            ApplicantIndividualCompanyRelation::firstOrCreate(
                $companyRelation
            );
        }
    }
}
