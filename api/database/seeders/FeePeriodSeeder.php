<?php

namespace Database\Seeders;

use App\Enums\FeePeriodEnum;
use App\Models\FeePeriod;
use Illuminate\Database\Seeder;

class FeePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feesPeriod = [
            FeePeriodEnum::DAILY->value,
            FeePeriodEnum::WEEKLY->value,
            FeePeriodEnum::MONTHLY->value,
            FeePeriodEnum::YEARLY->value,
            FeePeriodEnum::OTHER_SCHEDULE->value,
        ];

        foreach ($feesPeriod as $period) {
            FeePeriod::query()->firstOrCreate(['name' => FeePeriodEnum::tryFrom($period)->toString()]);
        }
    }
}
