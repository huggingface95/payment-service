<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\GuardEnum;
use App\Models\Permissions;


class InitPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $arrayOfPermissions = [
            [
                'name'=>  'Applicant.Individual list.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Individual list'
            ],
            [
                'name'=>  'Applicant.Individual list.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Individual list'
            ],
            [
                'name'=>  'Applicant.Company list.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Company list'
            ],
            [
                'name'=>  'Applicant.Company list.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Company list'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.General Info.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.General Info.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Api settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Api settings.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.KYC Timeline.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.KYC Timeline.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Documents.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Documents.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Check.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Check.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Accounts.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Accounts.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Account Details.Account Details Form.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Account Details.Account Details Form.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Account Details.Balance.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Account Details.Provider Info.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Open Account.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Requisites.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Requisites.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Statement.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Statement.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Payments.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Payments.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Payment Details.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Payment Details.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Make Payments.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Make Deposits.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Templates.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Individual.Banking.Templates.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Individual'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.General Info.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.General Info.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Api settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Api settings.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.KYC Timeline.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.KYC Timeline.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Documents.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Documents.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Check.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Check.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Accounts.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Accounts.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Account Details.Account Details Form.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Account Details.Account Details Form.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Account Details.Balance.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Account Details.Provider Info.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Open Account.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Requisites.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Requisites.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Statement.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Statement.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Payments.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Payments.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Payment Details.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Payment Details.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Make Payments.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Make Deposits.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Templates.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Applicant.Full Profile Company.Banking.Templates.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Full Profile Company'
            ],
            [
                'name'=>  'Settings.Manager Roles.Roles List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Manager Roles'
            ],
            [
                'name'=>  'Settings.Manager Roles.Roles Settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Manager Roles'
            ],
            [
                'name'=>  'Settings.Manager Roles.Roles Settings.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Manager Roles'
            ],
            [
                'name'=>  'Settings.Manager Groups.Groups List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Manager Groups'
            ],
            [
                'name'=>  'Settings.Manager Groups.Groups Settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Manager Groups'
            ],
            [
                'name'=>  'Settings.Manager Groups.Groups Settings.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Manager Groups'
            ],
            [
                'name'=>  'Settings.Payment System List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment System List'
            ],
            [
                'name'=>  'Settings.Payment System List.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment System List'
            ],
            [
                'name'=>  'Administration.Member Company Info.Company List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Member Company Info'
            ],
            [
                'name'=>  'Administration.Member Company Info.Company List.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Member Company Info'
            ],
            [
                'name'=>  'Administration.Member Company Info.Company Full Profile.Business Information.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Member Company Info'
            ],
            [
                'name'=>  'Administration.Member Company Info.Company Full Profile.Business Information.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Member Company Info'
            ],
            [
                'name'=>  'Administration.Member Company Info.Company Full Profile.Branding.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Member Company Info'
            ],
            [
                'name'=>  'Administration.Member Company Info.Company Full Profile.Branding.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Member Company Info'
            ],
            [
                'name'=>  'Administration.Members Info.Member List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Members Info'
            ],
            [
                'name'=>  'Administration.Members Info.Member List.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Members Info'
            ],
            [
                'name'=>  'Administration.Members Info.Member Full Profile.Profile.General.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Members Info'
            ],
            [
                'name'=>  'Administration.Members Info.Member Full Profile.Profile.General.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Members Info'
            ],
            [
                'name'=>  'Administration.Members Info.Member Full Profile.Profile.Settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Members Info'
            ],
            [
                'name'=>  'Administration.Members Info.Member Full Profile.Profile.Settings.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Members Info'
            ],
            [
                'name'=>  'Administration.Members Info.Logs.Active Sessions.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Members Info'
            ],
            [
                'name'=>  'Administration.Members Info.Logs.Authentication Log.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Members Info'
            ],
            [
                'name'=>  'Administration.Calendar.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Calendar'
            ],
            [
                'name'=>  'Administration.Calendar.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Calendar'
            ],
            [
                'name'=>  'Administration.Kanban.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Kanban'
            ],
            [
                'name'=>  'Administration.Kanban.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Kanban'
            ],
            [
                'name'=>  'Administration.Mail.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Mail'
            ],
            [
                'name'=>  'Administration.Logs.Active Sessions.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Logs'
            ],
            [
                'name'=>  'Administration.Logs.Activity Log.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Logs'
            ],

            [
                'name'=>  'Banking.Accounts List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Accounts List'
            ],
            [
                'name'=>  'Banking.Open Account.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Open Account'
            ],
            [
                'name'=>  'Banking.Account Details.Account Details Form.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Account Details'
            ],
            [
                'name'=>  'Banking.Account Details.Account Details Form.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Account Details'
            ],
            [
                'name'=>  'Banking.Account Details.Balance.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Account Details'
            ],
            [
                'name'=>  'Banking.Account Details.Provider Info.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Account Details'
            ],
            [
                'name'=>  'Banking.Requisites.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Requisites'
            ],
            [
                'name'=>  'Banking.Requisites.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Requisites'
            ],
            [
                'name'=>  'Banking.Statement.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Statement'
            ],
            [
                'name'=>  'Banking.Statement.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Statement'
            ],
            [
                'name'=>  'Banking.Payments.Payments List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payments'
            ],
            [
                'name'=>  'Banking.Payments.Payments List.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payments'
            ],
            [
                'name'=>  'Banking.Payments.Payment Details.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payments'
            ],
            [
                'name'=>  'Banking.Payments.Payment Details.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payments'
            ],
            [
                'name'=>  'Banking.Payments.Make Payments.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payments'
            ],
            [
                'name'=>  'Banking.Payments.Make Deposits.Yes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payments'
            ],
            [
                'name'=>  'Banking.Payment Provider.Providers List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Payment Provider Settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Payment Provider Settings.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Commission Templates.List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Commission Templates.List.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Commission Templates.Settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Commission Templates.Settings.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Commission Templates.Limits.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Commission Templates.Limits.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Commission Templates.Price List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Payment Provider.Provider Full Profile.Commission Templates.Price List.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Payment Provider'
            ],
            [
                'name'=>  'Banking.Commission Price List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Commission Price List'
            ],
            [
                'name'=>  'Banking.Commission Price List.Full',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Commission Price List'
            ],

        ];
        Permissions::insert($arrayOfPermissions);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
