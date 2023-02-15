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
        EmailTemplate::firstOrCreate([
            'id' => 1,
            'subject' => 'Welcome! Confirm your email address',
        ], [
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'member_id' => 2,
            'company_id' => 1,
            'created_at' => Carbon::now(),
            'name' => 'Test template',
        ]);

        EmailTemplate::firstOrCreate([
            'id' => 2,
            'subject' => 'Waiting for approval',
        ], [
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'member_id' => 2,
            'company_id' => 1,
            'created_at' => Carbon::now(),
            'name' => 'Test template approval',
        ]);

        EmailTemplate::firstOrCreate([
            'id' => 3,
            'subject' => 'Reset Password',
        ], [
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'member_id' => 2,
            'company_id' => 1,
            'created_at' => Carbon::now(),
            'name' => 'Test template reset password',
        ]);

        EmailTemplate::firstOrCreate([
            'id' => 4,
            'subject' => 'Welcome! Confirm your email address',
        ], [
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'member_id' => 3,
            'company_id' => 1,
            'created_at' => Carbon::now(),
            'name' => 'Test template 2',
        ]);

        EmailTemplate::firstOrCreate([
            'id' => 5,
            'subject' => 'Waiting for approval',
        ], [
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'member_id' => 3,
            'company_id' => 1,
            'created_at' => Carbon::now(),
            'name' => 'Test template approval 2',
        ]);

        EmailTemplate::firstOrCreate([
            'id' => 6,
            'subject' => 'Reset Password',
        ], [
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'member_id' => 3,
            'company_id' => 1,
            'created_at' => Carbon::now(),
            'name' => 'Test template reset password 2',
        ]);

        EmailTemplate::firstOrCreate([
            'id' => 7,
            'subject' => '{member_company_name} has invited you to join team',
        ], [
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'member_id' => 3,
            'company_id' => 1,
            'created_at' => Carbon::now(),
            'name' => 'has invited you to join team',
        ]);

        EmailTemplate::firstOrCreate([
            'id' => 8,
            'subject' => '{member_company_name} has invited you to join team',
        ], [
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'member_id' => 3,
            'company_id' => 1,
            'created_at' => Carbon::now(),
            'name' => 'has invited you to join team 2',
        ]);
    }
}
