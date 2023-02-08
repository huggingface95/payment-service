<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use App\Models\PaymentSystem;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSystemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            PaymentSystem::firstOrCreate([
                'id' => $i,
            ], [
                'name' => $faker->company().'Pay',
                'is_active' => true,
                'payment_provider_id' => $i,
            ]);
        }

        $seq = DB::table('payment_system')->max('id') + 1;
        DB::select('ALTER SEQUENCE payment_system_id_seq RESTART WITH '.$seq);

        PaymentSystem::firstOrCreate([
            'name' => 'Internal',
            'is_active' => true,
            'payment_provider_id' => PaymentProvider::where('name', 'Internal')->first()->id,
        ]);
    }
}
