<?php

namespace Database\Seeders;

use App\Models\TransferType;
use Illuminate\Database\Seeder;

class TransferTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transferType = ['Incoming Transfer', 'Outgoing Transfer'];
        foreach ($transferType as $item) {
            TransferType::firstOrCreate(['name'=>$item]);
        }
    }
}
