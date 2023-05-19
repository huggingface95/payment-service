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
        $oauthClients = [
            [
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => '',
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => 0,
            ], [
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'member',
                'redirect' => 'http://localhost',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => 0,
            ], [
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'applicant',
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => 0,
            ], [
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'applicant',
                'redirect' => 'http://localhost',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => 0,
            ], [
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'member',
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => 0,
            ], [
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'corporate',
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => 0,
            ], [
                'name' => 'Docudots',
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'corporate',
                'redirect' => 'http://localhost',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => 0,
            ],
        ];

        foreach ($oauthClients as $oaClient) {
            OauthClient::query()->firstOrCreate($oaClient);
        }
    }
}
