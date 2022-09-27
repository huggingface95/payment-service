<?php

namespace Database\Seeders;

use App\Models\Payments;
use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Payments::factory()->count(10)->create();
    }
}
