<?php

namespace Database\Seeders;

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
        PaymentUrgency::create([
            'id' => 1,
            'name' => 'Standart',
        ]);

        PaymentUrgency::create([
            'id' => 2,
            'name' => 'Urgent',
        ]);
    }
}
