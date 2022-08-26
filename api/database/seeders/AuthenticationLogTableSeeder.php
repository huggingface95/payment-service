<?php

namespace Database\Seeders;

use App\Models\Clickhouse\AuthenticationLog;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthenticationLogTableSeeder extends Seeder
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
            DB::connection('clickhouse')
                ->table((new AuthenticationLog)->getTable())
                ->insert([
                    'id' => $i,
                    'company' => $faker->randomElement(['Nginx', 'Apple', 'Nike']),
                    'member' => $faker->randomElement(['feder@gmail.com', 'jover@gmail.com', 'dan@gmail.com']),
                    'group' => $faker->randomElement(['Member', 'Admin']),
                    'domain' => $faker->domainName,
                    'ip' => $faker->ipv4,
                    'country' => $faker->country,
                    'city' => $faker->city,
                    'platform' => $faker->randomElement(['Windows', 'iOS', 'macOS']),
                    'browser' => $faker->randomElement(['Opera', 'Chrome', 'Firefox']),
                    'device_type' => $faker->randomElement(['Desktop', 'Tablet']),
                    'model' => $faker->randomElement(['Android', 'iOS', 'Windows Desktop']),
                    'status' => $faker->randomElement(['login', 'logout']),
                    'info' => $faker->randomElement(['info 1', 'info 2']),
                    'expired_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                    'created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                ]);
        }
    }
}
