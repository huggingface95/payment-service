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
        $this->call(CountryTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(GroupTableSeeder::class);
        $this->call(TwoFactorAuthTableSeeder::class);
        $this->call(PaymentSystemTableSeeder::class);
        $this->call(PaymentProviderTableSeeder::class);
        $this->call(ApplicantRiskLevelTableSeeder::class);
        $this->call(ApplicantStateReasonTableSeeder::class);
        $this->call(ApplicantStateTableSeeder::class);
        $this->call(ApplicantStatusTableSeeder::class);
        $this->call( ApplicantLabelsTableSeeder::class);
        $this->call( ApplicantCompanyLabelsTableSeeder::class);
    }
}
