<?php

namespace Database\Seeders;

use App\Enums\TransferTypeEnum;
use App\Models\TransferType;
use Illuminate\Database\Seeder;

class TransferTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = TransferTypeEnum::cases();

        foreach ($types as $type) {
            TransferType::firstOrCreate([
                'name' => $type->toString(),
            ]);
        }
    }
}
