<?php

namespace Database\Seeders;

use App\Models\Company;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
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
            Company::firstOrCreate(
                [
                    'name' => 'Company ' . $i,
                ],
                [
                    'email' => $faker->email(),
                    'company_number' => $faker->randomDigit(),
                    'zip' => $faker->postcode(),
                    'address' => $faker->address(),
                    'city' => $faker->city(),
                    'country_id' => $i,
                    'contact_name' => $faker->name,
                    'url' => 'https://' . $faker->domainName(),
                ]
            );
        }
    }
}
