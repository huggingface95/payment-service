<?php

namespace Database\Seeders;

use App\Enums\MemberStatusEnum;
use App\Models\MemberStatus;
use Illuminate\Database\Seeder;

class MemberStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = MemberStatusEnum::cases();

        foreach ($statuses as $status) {
            MemberStatus::firstOrCreate([
                'id' => $status->value,
                'name' => $status->toString(),
            ]);
        }
    }
}
