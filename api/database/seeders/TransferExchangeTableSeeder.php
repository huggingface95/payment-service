<?php

namespace Database\Seeders;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\TransferExchange;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use App\Traits\TransferHistoryTrait;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TransferExchangeTableSeeder extends Seeder
{
    use TransferHistoryTrait;

    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amount = $this->faker->randomNumber(3);

        $paymentNumber = 'EXCH'.rand();

        $accountIds = [3, 4];
        $uniqueAccountPairs = $this->faker->randomElements($accountIds, 2, false);

        $fromAccountId = $uniqueAccountPairs[0];
        $toAccountId = $uniqueAccountPairs[1];

        for ($i = 1; $i <= 10; $i++) {
            $transferExchange = TransferExchange::firstOrCreate([
                'client_id' => $fromAccountId,
                'requested_by_id' => $toAccountId,
                'debited_account_id' => $fromAccountId,
                'credited_account_id' => $toAccountId,
                'company_id' => 1,
                'status_id' => PaymentStatusEnum::UNSIGNED->value,
                'transfer_outgoing_id' => 1,
                'transfer_incoming_id' => 1,
                'exchange_rate' => 1.01512,
                'user_type' => 'Members',
                'client_type' => 'ApplicantIndividual',
            ]);

            $uniqueAccountPairs = $this->faker->randomElements($accountIds, 2, false);
            $fromAccountId = $uniqueAccountPairs[0];
            $toAccountId = $uniqueAccountPairs[1];

            $transferIncoming = null;

            TransferIncoming::withoutEvents(function () use ($amount, $paymentNumber, $toAccountId, &$transferIncoming) {
                $payment = TransferIncoming::factory()->definition();
                $payment['amount'] = $amount;
                $payment['amount_debt'] = $payment['amount'];
                $payment['payment_number'] = $paymentNumber;
                $payment['created_at'] = Carbon::now();
                $payment['operation_type_id'] = OperationTypeEnum::EXCHANGE->value;
                $payment['status_id'] = PaymentStatusEnum::UNSIGNED->value;
                $payment['account_id'] = $toAccountId;
                $payment['recipient_id'] = 2;

                $transferIncoming = TransferIncoming::query()->firstOrCreate(
                    $payment
                );
            });

            $transferOutgoing = null;

            TransferOutgoing::withoutEvents(function () use ($amount, $paymentNumber, $toAccountId, &$transferOutgoing) {
                $payment = TransferOutgoing::factory()->definition();
                $payment['amount'] = $amount;
                $payment['amount_debt'] = $payment['amount'];
                $payment['payment_number'] = $paymentNumber;
                $payment['created_at'] = Carbon::now();
                $payment['operation_type_id'] = OperationTypeEnum::EXCHANGE->value;
                $payment['status_id'] = PaymentStatusEnum::UNSIGNED->value;
                $payment['account_id'] = $toAccountId;
                $payment['requested_by_id'] = 2;
                $payment['sender_id'] = 2;

                $transferOutgoing = TransferOutgoing::query()->firstOrCreate(
                    $payment
                );
            });

            $transferExchange->transfer_incoming_id = $transferIncoming->id;
            $transferExchange->transfer_outgoing_id = $transferOutgoing->id;
            $transferExchange->requested_by_id = 2;
            $transferExchange->save();

            $this->createTransferHistory($transferOutgoing)->createPPHistory($transferOutgoing);
            $this->createTransferHistory($transferIncoming)->createPPHistory($transferIncoming);
        }
    }
}
