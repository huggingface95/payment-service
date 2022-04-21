<?php

namespace Database\Seeders;

use App\Models\PaymentTypes;
use Illuminate\Database\Seeder;

class PaymentTypeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Incoming','Outgoing','Fee','Between Account'];
        foreach ($types as $type)
        {
            PaymentTypes::create([
              'name'=>$type
            ]);
        }
    }
}
