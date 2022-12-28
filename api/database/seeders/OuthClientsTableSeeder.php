<?php

namespace Database\Seeders;

use App\Models\DepartmentPosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OuthClientsTableSeeder extends Seeder
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
                'user_id' => 2,
                'name' => 'Docudots',
                'secret' => 'YiO0GgWeDCdbqB9vOFtYKVzh28fKiQIVEKnjJ7Py',
                'provider' => '',
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
            ], [
                'user_id' => 2,
                'name' => 'Docudots',
                'secret' => 'qYnR2NPDoJ8iCpPLm681nTmY2uI5XhiyNr1qgURw',
                'provider' => 'member',
                'redirect' => 'http://localhost',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => false,
            ], [
                'user_id' => 2,
                'name' => 'Docudots',
                'secret' => 'qYnR2NPDoJ8iCpPLm681nTmY2uI5XhiyNr1qgURa',
                'provider' => 'applicant',
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
            ], [
                'user_id' => 2,
                'name' => 'Docudots',
                'secret' => 'qYnR2NPDoJ8iCpPLm681nTmY2uI5XhiyNr1qgURs',
                'provider' => 'applicant',
                'redirect' => 'http://localhost',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => false,
            ], [
                'user_id' => 2,
                'name' => 'Docudots',
                'secret' => 'qYnR2NPDoJ8iCpPLm681nTmY2uI5XhiyNr1qgURh',
                'provider' => 'member',
                'redirect' => 'http://localhost',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
            ],
        ];

        foreach ($oauthClients as $oauthClient) {
            DB::connection('pgsql_test')->table('oauth_clients')->insert($oauthClient);
        }
    }
}
