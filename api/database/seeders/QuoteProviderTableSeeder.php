<?php

namespace Database\Seeders;

use App\Enums\ActivityStatusEnum;
use App\Enums\QuoteTypeEnum;
use App\Models\QuoteProvider;
use Faker\Factory;
use Illuminate\Database\Seeder;

class QuoteProviderTableSeeder extends Seeder
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
            QuoteProvider::updateOrCreate([
                'id' => $i,
            ], [
                'name' => 'Quote Provider ' . $i,
                'company_id' => $i,
                'status' => $faker->randomElement([ActivityStatusEnum::INACTIVE->value, ActivityStatusEnum::ACTIVE->value]),
                'quote_type' => $faker->randomElement([QuoteTypeEnum::API->toString(), QuoteTypeEnum::MANUAL->toString()]),
            ]);
        }
    }
}
