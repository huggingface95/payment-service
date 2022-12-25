<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TicketTableSeeder extends Seeder
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
            Ticket::firstOrCreate(
                [
                    'id' => $i,
                ],
                [
                    'member_id' => 1,
                    'client_id' => 1,
                    'title' => 'Subject ' . $i,
                    'message' => $faker->text(),
                    'status' => $faker->numberBetween(1, 4),
                    'created_at' => $date = $faker->dateTime()->format('Y-m-d H:i:s'),
                    'updated_at' => $date,
                ]
            );
        }
    }
}
