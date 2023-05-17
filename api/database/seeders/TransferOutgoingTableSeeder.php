<?php

namespace Database\Seeders;

use App\Models\TransferOutgoing;
use Illuminate\Database\Seeder;

class TransferOutgoingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransferOutgoing::withoutEvents(function () {
            for ($i = 1; $i <= 10; $i++) {
                $payment = TransferOutgoing::factory()->definition();
                $payment['payment_number'] = '1000'.$i;

                TransferOutgoing::query()->firstOrCreate(
                    [
                        'payment_number' => '1000'.$i,
                    ],
                    $payment
                );
            }
        });
    }
}
