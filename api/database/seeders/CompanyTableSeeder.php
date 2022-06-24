<?php

namespace Database\Seeders;

use App\Models\Companies;
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
            Companies::create([
                'id'        => $i,
                'name'     => $faker->company,
                'email'    => $faker->email,
                'company_number' => $faker->randomDigit(),
                'zip' => $faker->postcode,
                'address' => $faker->address,
                'city' => $faker->city,
                'country_id' => $i,
            ]);
        }
    }
}
