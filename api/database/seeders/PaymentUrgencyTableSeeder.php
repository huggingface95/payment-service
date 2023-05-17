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
        PaymentUrgency::query()->updateOrCreate([
            'name' => PaymentUrgencyEnum::STANDART->toString(),
        ]);

        PaymentUrgency::query()->updateOrCreate([
            'name' => PaymentUrgencyEnum::EXPRESS->toString(),
        ]);
    }
}
