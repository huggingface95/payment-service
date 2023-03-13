<?php

namespace Database\Seeders;

use App\Models\MemberAccessLimitation;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferFileRelationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        DB::table('transfer_file_relation')->insert([
            'transfer_id' => 1,
            'transfer_type' => 'TransferIncoming',
            'file_id' => 1,
        ]);

        DB::table('transfer_file_relation')->insert([
            'transfer_id' => 2,
            'transfer_type' => 'TransferOutgoing',
            'file_id' => 1,
        ]);

        for ($i = 3; $i <= 10; $i++) {
            DB::table('transfer_file_relation')->insert([
                'transfer_id' => $i,
                'transfer_type' => $faker->randomElement(['TransferOutgoing', 'TransferIncoming']),
                'file_id' => $faker->numberBetween(1, 2),
            ]);
        }
    }
}
