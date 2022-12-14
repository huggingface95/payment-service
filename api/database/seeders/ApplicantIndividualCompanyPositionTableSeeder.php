<?php

namespace Database\Seeders;

use App\Models\ApplicantIndividualCompanyPosition;
use Illuminate\Database\Seeder;

class ApplicantIndividualCompanyPositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicantIndividualCompanyPosition::truncate();

        $companyPositions = [
            ['name'=>'Chief', 'company_id' => 1],
            ['name'=>'Operating Officer', 'company_id' => 1],
            ['name'=>'Chief Executive Officer', 'company_id' => 1],
            ['name'=>'Treasure', 'company_id' => 1],
            ['name'=>'Director', 'company_id' => 1],
            ['name'=>'Secretary', 'company_id' => 1],
            ['name'=>'Managing director', 'company_id' => 1],
        ];

        foreach ($companyPositions as $companyPosition) {
            ApplicantIndividualCompanyPosition::firstOrCreate(
                $companyPosition
            );
        }
    }
}
