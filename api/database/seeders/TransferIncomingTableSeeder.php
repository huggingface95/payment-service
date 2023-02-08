<?php

namespace Database\Seeders;

use App\Models\TransferIncoming;
use Illuminate\Database\Seeder;

class TransferIncomingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransferIncoming::withoutEvents(function () {
            for ($i = 1; $i <= 20; $i++) {
                $payment = TransferIncoming::factory()->definition();
                $payment['id'] = $i;
                $payment['payment_number'] = '2000'.$i;

                TransferIncoming::firstOrCreate(
                    [
                        'id' => $i,
                        'payment_number' => '2000'.$i,
                    ],
                    $payment
                );
            }
        });
    }
}
