<?php

namespace Database\Seeders;

use App\Enums\FeeTransferTypeEnum;
use App\Models\PaymentProviderHistory;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PaymentProviderHistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        PaymentProviderHistory::firstOrCreate([
            'payment_provider_id' => 1,
            'transfer_id' => 1,
            'transfer_type' => FeeTransferTypeEnum::INCOMING->toString(),
        ]);

        PaymentProviderHistory::firstOrCreate([
            'payment_provider_id' => 2,
            'transfer_id' => 2,
            'transfer_type' => FeeTransferTypeEnum::OUTGOING->toString(),
        ]);

        for ($i = 3; $i <= 10; $i++) {
            PaymentProviderHistory::firstOrCreate([
                'payment_provider_id' => $i,
                'transfer_id' => $i,
                'transfer_type' => $faker->randomElement([FeeTransferTypeEnum::OUTGOING->toString(), FeeTransferTypeEnum::INCOMING->toString()]),
            ]);
        }
    }
}
