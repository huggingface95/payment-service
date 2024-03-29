<?php

namespace Database\Seeders;

use App\Models\CommissionTemplate;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CommissionTemplateTableSeeder extends Seeder
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
            CommissionTemplate::firstOrCreate(
                [
                    'name' => 'Template '.$i,
                ],
                [
                    'description' => $faker->name(),
                    'payment_provider_id' => $i,
                    'is_active' => true,
                    'member_id' => 2,
                    'company_id' => 1,
                ]
            );
        }
    }
}
