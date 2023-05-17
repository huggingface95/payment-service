<?php

namespace Database\Seeders;

use App\Enums\TransferHistoryActionEnum;
use App\Models\TransferIncoming;
use App\Models\TransferIncomingHistory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Traits\TransferHistoryTrait;

class TransferIncomingTableSeeder extends Seeder
{
    use TransferHistoryTrait;
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

                $transferIncoming = TransferIncoming::find(1);
                TransferIncomingHistory::firstOrCreate([
                    'transfer_id' => $transferIncoming->id,
                    'status_id' => $transferIncoming->status_id,
                    'action' => TransferHistoryActionEnum::INIT->value,
                    'created_at' => Carbon::now(),
                ]);
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

                $transferIncoming = TransferIncoming::find($i);
                TransferIncomingHistory::firstOrCreate([
                    'transfer_id' => $transferIncoming->id,
                    'status_id' => $transferIncoming->status_id,
                    'action' => TransferHistoryActionEnum::INIT->value,
                    'created_at' => Carbon::now(),
                ]);
            }
        });
    }
}
