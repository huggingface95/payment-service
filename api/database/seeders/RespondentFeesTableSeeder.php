<?php

namespace Database\Seeders;

use App\Enums\RespondentFeesEnum;
use App\Models\RespondentFee;
use Illuminate\Database\Seeder;

class RespondentFeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fees = RespondentFeesEnum::cases();

        foreach ($fees as $fee) {
            RespondentFee::create([
                'id' => $fee->value,
                'name' => $fee->toString(),
            ]);
        }
    }
}
