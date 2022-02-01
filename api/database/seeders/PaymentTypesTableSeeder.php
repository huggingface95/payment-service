<?php

namespace Database\Seeders;

use App\Models\PaymentTypes;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PaymentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentTypes::create([
            'id' => 1,
            'name' => 'Incoming'
        ]);

        PaymentTypes::create([
            'id' => 2,
            'name' => 'Outgoing'
        ]);

        PaymentTypes::create([
            'id' => 3,
            'name' => 'Between Account'
        ]);

        PaymentTypes::create([
            'id' => 4,
            'name' => 'Fee'
        ]);

    }

}
