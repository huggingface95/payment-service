<?php

namespace Database\Seeders;

use App\Models\Members;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Members::insert([
            'id' => 2,
            'first_name' => 'Member2',
            'last_name' => 'Member2 Last',
            'email' => 'test@test.com',
            'password_hash' => Hash::make('1234567Qa'),
            'password_salt' => '4561654sd654f65d4f',
            'company_id' => 2,
            'two_factor_auth_setting_id' => 1,
        ]);
    }
}