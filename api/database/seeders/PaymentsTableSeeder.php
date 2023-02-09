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
        Payments::withoutEvents(function () {
            for ($i = 1; $i <= 10; $i++) {
                $payment = Payments::factory()->definition();
                $payment['id'] = $i;
                $payment['payment_number'] = '1000'.$i;

                Payments::firstOrCreate(
                    [
                        'id' => $i,
                        'payment_number' => '1000'.$i,
                    ],
                    $payment
                );
            }
        });
    }
}
