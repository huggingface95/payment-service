<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EmailTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailTemplate::insert([
            'id' => 1,
            'subject' => 'Waiting for approval',
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'member_id' => 2,
            'company_id' => 1,
            'created_at' => Carbon::now(),
            'name' => 'Test template',
        ]);
    }
}
