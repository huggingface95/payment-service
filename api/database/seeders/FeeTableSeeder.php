<?php

namespace Database\Seeders;

use App\Enums\FeeTransferTypeEnum;
use App\Models\Fee;
use Illuminate\Database\Seeder;

class FeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fee::firstOrCreate([
            'transfer_id' => 1,
            'fee' => 750,
            'fee_pp' => 750 * 0.9,
            'fee_type_id' => 1,
            'transfer_type' => FeeTransferTypeEnum::INCOMING->toString(),
            'operation_type_id' => 2,
            'member_id' => null,
            'status_id' => 1,
            'client_id' => 1,
            'client_type' => class_basename(ApplicantIndividual::class),
            'account_id' => 1,
            'price_list_fee_id' => 1,
        ]);

        Fee::firstOrCreate([
            'transfer_id' => 2,
            'fee' => 560,
            'fee_pp' => 560 * 0.9,
            'fee_type_id' => 1,
            'transfer_type' => FeeTransferTypeEnum::OUTGOING->toString(),
            'operation_type_id' => 1,
            'member_id' => null,
            'status_id' => 1,
            'client_id' => 1,
            'client_type' => class_basename(ApplicantIndividual::class),
            'account_id' => 1,
            'price_list_fee_id' => 2,
        ]);

        Fee::withoutEvents(function () {
            for ($i = 3; $i <= 8; $i++) {
                $fee = Fee::factory()->definition();

                Fee::firstOrCreate(
                    [
                        'transfer_id' => $i,
                        'operation_type_id' => $i,
                    ],
                    $fee
                );
            }
        });
    }
}
