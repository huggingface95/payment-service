<?php

namespace Database\Seeders;

use App\Models\Clickhouse\ActiveSession;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActiveSessionTestDbTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 30; $i++) {
            DB::connection('clickhouse_test')
                ->table((new ActiveSession())->getTable())
                ->insert([
                    'id' => $faker->uuid(),
                    'company' => $faker->randomElement(['Nginx', 'Apple', 'Nike']),
                    'email' => $faker->randomElement(['feder@gmail.com', 'jover@gmail.com', 'dan@gmail.com', 'applicant1@test.com']),
                    'provider' => $faker->randomElement(['Member', 'Admin', 'individual']),
                    'ip' => $faker->ipv4,
                    'country' => $faker->country,
                    'city' => $faker->city,
                    'platform' => $faker->randomElement(['Windows', 'iOS', 'macOS']),
                    'browser' => $faker->randomElement(['Opera', 'Chrome', 'Firefox']),
                    'device_type' => $faker->randomElement(['Desktop', 'Tablet']),
                    'model' => $faker->randomElement(['Android', 'iOS', 'Windows Desktop']),
                    'created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                ]);
        }
    }
}
