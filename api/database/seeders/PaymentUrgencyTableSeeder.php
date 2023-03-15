<?php

namespace Database\Seeders;

use App\Enums\PaymentUrgencyEnum;
use App\Models\PaymentUrgency;
use Illuminate\Database\Seeder;

class PaymentUrgencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentUrgency::updateOrCreate([
            'id' => 1,
        ], [
            'name' => PaymentUrgencyEnum::STANDART->toString(),
        ]);

        PaymentUrgency::updateOrCreate([
            'id' => 2,
        ], [
            'name' => PaymentUrgencyEnum::EXPRESS->toString(),
        ]);
    }
}
