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
        $oaClients = [
            [
                'secret' => Hash::make(rand(100, 999)),
                'provider' => null,
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
            ], [
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'member',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
            ], [
                'secret' => Hash::make(rand(100, 999)),
                'provider' => 'applicant',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
            ],
        ];

        $i = 1;
        foreach ($oaClients as $oaClient) {
            OauthClient::firstOrCreate([
                'id' => $i,
                'name' => 'Docudots',
            ], $oaClient);

            $i++;
        }
    }
}
