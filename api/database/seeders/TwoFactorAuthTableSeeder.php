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
        TwoFactorAuthSettings::create(
            ['name' => 'Disabled']
        );
        TwoFactorAuthSettings::create(
            ['name' => 'GA Enabled']
        );
    }
}
