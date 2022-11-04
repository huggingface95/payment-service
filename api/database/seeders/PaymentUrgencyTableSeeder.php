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
        PaymentUrgency::firstOrCreate([
            'id' => 1,
            'name' => 'Standart',
        ]);

        PaymentUrgency::firstOrCreate([
            'id' => 2,
            'name' => 'Urgent',
        ]);
    }
}
