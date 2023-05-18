<?php

namespace Database\Seeders;

use App\Models\AccountClient;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Seeder;

class AccountClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountClient::firstOrCreate(
            [
                'client_id' => 1,
                'client_type' => class_basename(ApplicantIndividual::class),
            ]
        );

        AccountClient::firstOrCreate(
            [
                'client_id' => 2,
                'client_type' => class_basename(ApplicantCompany::class),
            ]
        );
    }
}
