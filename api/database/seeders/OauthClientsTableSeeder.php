<?php

namespace Database\Seeders;

use App\Models\OauthClient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OauthClient::insert([
            [
                'id' => 1,
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => null,
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
            ], [
                'id' => 2,
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'member',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
            ], [
                'id' => 3,
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'applicant',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
            ],
        ]);
    }
}
