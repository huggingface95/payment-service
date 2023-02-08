<?php

namespace Database\Seeders;

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
        Fee::withoutEvents(function () {
            for ($i = 1; $i <= 20; $i++) {
                $fee = Fee::factory()->definition();

                Fee::firstOrCreate(
                    [
                        'transfer_id' => $i,
                    ],
                    $fee
                );
            }
        });
    }
}
