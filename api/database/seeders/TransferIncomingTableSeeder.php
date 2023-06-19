<?php

namespace Database\Seeders;

use App\Enums\TransferHistoryActionEnum;
use App\Models\PaymentProviderHistory;
use App\Models\TransferIncoming;
use App\Models\TransferIncomingHistory;
use App\Traits\TransferHistoryTrait;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
            $payment['payment_number'] = '20001';
            $payment['created_at'] = Carbon::now()->format('Y-m-d H:i:s');

            TransferIncoming::query()->firstOrCreate(
                    [
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

            PaymentProviderHistory::create([
                'payment_provider_id' => $transferIncoming->payment_provider_id,
                'transfer_id' => $transferIncoming->id,
                'transfer_type' => 'Incoming',
            ]);
        });

        TransferIncoming::withoutEvents(function () {
            for ($i = 2; $i <= 10; $i++) {
                $payment = TransferIncoming::factory()->definition();
                $payment['payment_number'] = '2000'.$i;

                TransferIncoming::query()->firstOrCreate(
                    [
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

                PaymentProviderHistory::create([
                    'payment_provider_id' => $transferIncoming->payment_provider_id,
                    'transfer_id' => $transferIncoming->id,
                    'transfer_type' => 'Incoming',
                ]);
            }
        });
    }
}
