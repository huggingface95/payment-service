<?php

namespace Database\Seeders;

use App\Models\TransferIncoming;
use Carbon\Carbon;
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
                $payment = TransferIncoming::factory()->definition();
                $payment['id'] = 1;
                $payment['payment_number'] = '20001';
                $payment['created_at'] = Carbon::now()->format('Y-m-d H:i:s');

                TransferIncoming::firstOrCreate(
                    [
                        'id' => 1,
                        'payment_number' => '20001',
                    ],
                    $payment
                );
        });

        TransferIncoming::withoutEvents(function () {
            for ($i = 2; $i <= 10; $i++) {
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
