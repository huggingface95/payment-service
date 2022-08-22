<?php

namespace Database\Seeders;

use App\Models\PermissionCategory;
use App\Models\PermissionsList;
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
        $this->call(ApplicantLabelsTableSeeder::class);
        $this->call(ApplicantCompanyLabelsTableSeeder::class);
        $this->call(PaymentStatusSeed::class);
        $this->call(FeeTypeSeeder::class);
        $this->call(FeePeriodSeeder::class);
        $this->call(OperationTypeSeeder::class);
        $this->call(AccountStatesTableSeeder::class);
        $this->call(CommissionTemplateLimitTypeTableSeeder::class);
        $this->call(CommissionTemplateTableSeeder::class);
        $this->call(MembersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(GroupRoleTableSeeder::class);
        $this->call(GroupRoleMembersIndividualsTableSeeder::class);
        $this->call(ApplicantIndividualTableSeeder::class);
        $this->call(ApplicantCompaniesTableSeeder::class);
        $this->call(EmailSmtpsTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
        $this->call(ApplicantIndividualCompanyTableSeeder::class);
        $this->call(EmailNotificationsTableSeeder::class);
        $this->call(EmailNotificationTemplatesTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(PermissionsSeeder::class);
    }
}
