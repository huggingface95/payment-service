<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailNotificationTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = DB::table('email_notification_templates')->where([
            'email_notification_id' => 1,
            'email_template_id' => 1,
        ])->first();

        if (! $row) {
            DB::table('email_notification_templates')->insert([
                'email_notification_id' => 1,
                'email_template_id' => 1,
            ]);
        }
    }
}
