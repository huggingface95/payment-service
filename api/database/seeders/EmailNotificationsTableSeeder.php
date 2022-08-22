<?php

namespace Database\Seeders;

use App\Models\EmailNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailNotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailNotification::insert([
            'company_id' => 1,
            'group_role_id' => 1,
            'group_type_id' => 2,
        ]);
    }
}
