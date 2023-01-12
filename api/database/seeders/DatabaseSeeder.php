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
        $this->call(PaymentProviderTableSeeder::class);
        $this->call(PaymentSystemTableSeeder::class);
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
        $this->call(DepartmentSeeder::class);
        $this->call(DepartmentPositionTableSeeder::class);
        $this->call(DepartmentPositionRelationSeeder::class);
        $this->call(MembersTableSeeder::class);
        $this->call(CommissionTemplateLimitTypeTableSeeder::class);
        $this->call(CommissionTemplateTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(SuperAdminSeeder::class);
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
        $this->call(RegionCountriesTableSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(ActiveSessionTableSeeder::class);
        $this->call(AuthenticationLogTableSeeder::class);
        $this->call(ActivityLogTableSeeder::class);
        $this->call(FeeModesSeeder::class);
        $this->call(CommissionPriceListTableSeeder::class);
        $this->call(PriceListFeesTableSeeder::class);
        $this->call(PriceListFeeCurrencyTableSeeder::class);
        $this->call(PaymentBankTableSeeder::class);
        $this->call(AccountTableSeeder::class);
        $this->call(ApplicantBankingAccessTableSeeder::class);
        $this->call(PaymentUrgencyTableSeeder::class);
        $this->call(RespondentFeesTableSeeder::class);
        $this->call(CommissionTemplateBusinessActivityTableSeeder::class);
        $this->call(RoleActionsSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(ApplicantIndividualCompanyPositionTableSeeder::class);
        $this->call(ApplicantIndividualCompanyRelationTableSeeder::class);
        $this->call(OuthClientsTableSeeder::class);
    }
}
