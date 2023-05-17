<?php

namespace Database\Seeders;

use App\Enums\TransferHistoryActionEnum;
use App\Models\TransferOutgoing;
use App\Models\TransferOutgoingHistory;
use Carbon\Carbon;
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

                $transferOutgoing = TransferOutgoing::find($i);
                TransferOutgoingHistory::firstOrCreate([
                    'transfer_id' => $transferOutgoing->id,
                    'status_id' => $transferOutgoing->status_id,
                    'action' => TransferHistoryActionEnum::INIT->value,
                    'created_at' => Carbon::now(),
                ]);
            }
        });
    }
}
