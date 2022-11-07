<?php

namespace Database\Seeders;

use App\Models\TwoFactorAuthSettings;
use Illuminate\Database\Seeder;

class TwoFactorAuthTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $twofas = [
            ['name' => 'Disabled'],
            ['name' => 'GA Enabled'],
        ];

        foreach ($twofas as $twofa) {
            TwoFactorAuthSettings::firstOrCreate(
                $twofa
            );
        }
    }
}
