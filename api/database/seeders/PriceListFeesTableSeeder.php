<?php

namespace Database\Seeders;

use App\Models\CommissionPriceList;
use App\Models\PriceListFee;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PriceListFeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $commissionPriceList = CommissionPriceList::first();

        for ($i = 1; $i <= 3; $i++) {
            PriceListFee::firstOrCreate([
                'id' => $i,
            ], [
                'name' => 'Test fee ' . $faker->sentence(1) . $faker->randomDigit(2),
                'price_list_id' => $commissionPriceList->id,
                'type_id' => 1,
                'operation_type_id' => 1,
                'period_id' => 1,
                'created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
