<?php

namespace Database\Seeders;

use App\Enums\FeeModeEnum;
use App\Models\FeeMode;
use Illuminate\Database\Seeder;

class FeeModesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feeModes = [
            FeeModeEnum::RANGE->value,
            FeeModeEnum::FIX->value,
            FeeModeEnum::PERCENT->value,
            FeeModeEnum::BASE->value,
            FeeModeEnum::PROVIDER->value,
            FeeModeEnum::QUOTEPROVIDER->value,
            FeeModeEnum::MARGIN->value,
        ];

        foreach ($feeModes as $mode) {
            FeeMode::firstOrCreate(['name' => FeeModeEnum::tryFrom($mode)->toString()]);
        }
    }
}
