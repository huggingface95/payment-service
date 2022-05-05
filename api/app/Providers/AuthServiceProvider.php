<?php

namespace App\Providers;

use App\Models\Accounts;
use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantCompany;
use App\Models\ApplicantCompanyBusinessType;
use App\Models\ApplicantCompanyLabel;
use App\Models\ApplicantCompanyModules;
use App\Models\ApplicantCompanyNotes;
use App\Models\ApplicantCompanyRiskLevelHistory;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualCompany;
use App\Models\ApplicantIndividualCompanyPosition;
use App\Models\ApplicantIndividualCompanyRelation;
use App\Models\ApplicantIndividualLabel;
use App\Models\ApplicantIndividualModules;
use App\Models\ApplicantIndividualNotes;
use App\Models\ApplicantKycLevel;
use App\Models\ApplicantModules;
use App\Models\ApplicantRiskLevel;
use App\Models\ApplicantRiskLevelHistory;
use App\Models\ApplicantState;
use App\Models\ApplicantStateReason;
use App\Models\ApplicantStatus;
use App\Models\BusinessActivity;
use App\Models\CommissionPriceList;
use App\Models\CommissionTemplate;
use App\Models\CommissionTemplateLimit;
use App\Models\CommissionTemplateLimitActionType;
use App\Models\CommissionTemplateLimitPeriod;
use App\Models\CommissionTemplateLimitTransferDirection;
use App\Models\CommissionTemplateLimitType;
use App\Models\Companies;
use App\Models\CompanySettings;
use App\Models\Country;
use App\Models\Currencies;
use App\Models\DepartmentPosition;
use App\Models\Departments;
use App\Models\EmailNotification;
use App\Models\EmailTemplate;
use App\Models\FeePeriod;
use App\Models\FeeType;
use App\Models\Files;
use App\Models\GroupRole;
use App\Models\Groups;
use App\Models\Languages;
use App\Models\Members;
use App\Models\OperationType;
use App\Models\PaymentProvider;
use App\Models\Payments;
use App\Models\PaymentStatus;
use App\Models\PaymentSystem;
use App\Models\PaymentTypes;
use App\Models\PaymentUrgency;
use App\Models\PermissionCategory;
use App\Models\Permissions;
use App\Models\PriceListFee;
use App\Models\Requisites;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketComments;
use App\Models\TwoFactorAuthSettings;
use App\Models\User;
use App\Policies\ApplicantIndividualPolicy;
use App\Policies\BasePolicy;
use App\Policies\MemberPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    private array $basePolicies = [
        ApplicantBankingAccess::class, ApplicantCompany::class, ApplicantCompanyBusinessType::class, ApplicantCompanyLabel::class,
        ApplicantCompanyModules::class, ApplicantCompanyNotes::class, ApplicantCompanyRiskLevelHistory::class, ApplicantCompanyRiskLevelHistory::class,
        ApplicantIndividualCompanyPosition::class, ApplicantIndividualCompanyRelation::class, ApplicantIndividualModules::class,
        ApplicantRiskLevelHistory::class, ApplicantKycLevel::class, ApplicantIndividualLabel::class, ApplicantModules::class,
        ApplicantIndividualNotes::class, ApplicantRiskLevel::class, ApplicantStateReason::class, ApplicantStatus::class, BusinessActivity::class,
        CommissionPriceList::class, CommissionTemplate::class, CommissionTemplateLimit::class, CommissionTemplateLimitActionType::class,
        CommissionTemplateLimitPeriod::class, CommissionTemplateLimitTransferDirection::class, CommissionTemplateLimitType::class,
        Companies::class, CompanySettings::class, Country::class, Currencies::class, Departments::class, DepartmentPosition::class,
        EmailNotification::class, EmailTemplate::class, FeePeriod::class, FeeType::class, Files::class, Groups::class, GroupRole::class,
        Languages::class, OperationType::class, PaymentProvider::class, PaymentStatus::class, PaymentSystem::class, PaymentTypes::class,
        PaymentUrgency::class, Permissions::class, PriceListFee::class, Requisites::class, Role::class, TicketComments::class, Ticket::class,
        TwoFactorAuthSettings::class,PermissionCategory::class,ApplicantCompanyBusinessType::class, ApplicantState::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->usePolicies();
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

//        $this->app['auth']->viaRequest('api', function ($request) {
//            if ($request->input('api_token')) {
//                return User::where('api_token', $request->input('api_token'))->first();
//            }
//            return null;
//        });
    }


    private function usePolicies()
    {
        foreach ($this->basePolicies as $model) {
            Gate::policy($model, BasePolicy::class);
        }

        Gate::policy(Payments::class, PaymentPolicy::class);
        Gate::policy(Members::class, MemberPolicy::class);
        Gate::policy(ApplicantIndividual::class, ApplicantIndividualPolicy::class);
        Gate::policy(ApplicantIndividualCompany::class, ApplicantIndividualCompany::class);

    }
}
