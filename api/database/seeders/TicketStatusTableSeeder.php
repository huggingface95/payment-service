<?php

namespace Database\Seeders;

use App\Enums\TicketStatusEnum;
use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class TicketStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = TicketStatusEnum::cases();

        foreach ($statuses as $status) {
            TicketStatus::firstOrCreate([
                'id' => $status->value,
                'name' => $status->toString(),
            ]);
        }
    }
}
