<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompanyTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(TwoFactorAuthTableSeeder::class);
        $this->call(GroupTableSeeder::class);
    }
}
