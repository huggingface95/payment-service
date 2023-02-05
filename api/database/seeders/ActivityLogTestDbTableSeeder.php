<?php

namespace Database\Seeders;

use App\Models\Clickhouse\ActivityLog;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityLogTestDbTableSeeder extends Seeder
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
                ->table((new ActivityLog)->getTable())
                ->insert([
                    'id' => $faker->uuid(),
                    'company' => $faker->randomElement(['Nginx', 'Apple', 'Nike']),
                    'member' => $faker->randomElement(['feder@gmail.com', 'jover@gmail.com', 'dan@gmail.com']),
                    'group' => $faker->randomElement(['Member', 'Admin']),
                    'domain' => $faker->randomElement(['CRM', 'KYC']),
                    'description' => $faker->paragraph(),
                    'changes' => $faker->paragraph(),
                    'created_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
                ]);
        }
    }
}
