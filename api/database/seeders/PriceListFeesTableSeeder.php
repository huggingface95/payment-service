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
                'name' => 'Test fee '.$i,
                'price_list_id' => $commissionPriceList->id,
            ], [
                'type_id' => 1,
                'operation_type_id' => $faker->randomElement([1, 2]),
                'period_id' => 1,
                'created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
