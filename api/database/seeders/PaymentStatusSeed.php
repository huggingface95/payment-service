<?php

namespace Database\Seeders;

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
        $statuses = ['Pending', 'Completed','Error','Canceled', 'Unsigned'];
        foreach ($statuses as $item) {
            PaymentStatus::create([
                'name' => $item
            ]);
        }
    }
}
