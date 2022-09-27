<?php

namespace Database\Seeders;

use App\Enums\PaymentStatusEnum;
use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentStatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = PaymentStatusEnum::cases();

        foreach ($statuses as $status) {
            PaymentStatus::create([
                'id' => $status->value,
                'name' => $status->toString(),
            ]);
        }
    }
}
