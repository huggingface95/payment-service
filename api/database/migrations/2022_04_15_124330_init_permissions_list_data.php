<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PermissionCategory;
use App\Models\PermissionsList;
use App\Models\Permissions;
use App\Enums\GuardEnum;

class InitPermissionsListData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $permissionManagementModule = PermissionCategory::create([
            'name' => 'Management Module'
        ]);
        $permissionSettingsModule = PermissionCategory::create([
            'name' => 'Settings Module'
        ]);
        $permissionAdminModule = PermissionCategory::create([
            'name' => 'Administration Module'
        ]);
        $permissionBankModule = PermissionCategory::create([
            'name' => 'Banking Module'
        ]);
        //Management module
        $applicantsIndividualList = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Applicants Individual List'
        ]);
        // Individual List
        $arrayOfPermissions = [
            [
                'name'=>  'Applicants Individual list.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $applicantsIndividualList->id
            ],
            [
                'name'=>  'Applicants Individual list.Export',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Export',
                'type'=>'export',
                'permission_list_id' => $applicantsIndividualList->id
            ],
            [
                'name'=>  'Applicants Individual list.Show Banking Info',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Show Banking Info',
                'type'=>'info',
                'permission_list_id' => $applicantsIndividualList->id
            ],
            [
                'name'=>  'Applicants Individual.Create New Individual',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Create New Individual',
                'type'=>'add',
                'permission_list_id' => $applicantsIndividualList->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //
        $applicantsCompanyList = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Applicants Company List'
        ]);
        //applicant company list
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Applicants Company list.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $applicantsCompanyList->id
            ],
            [
                'name'=>  'Applicants Company list.Export',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Export',
                'type'=>'export',
                'permission_list_id' => $applicantsCompanyList->id
            ],
            [
                'name'=>  'Applicants Company list.Show Banking Info',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Show Banking Info',
                'type'=>'info',
                'permission_list_id' => $applicantsCompanyList->id
            ],
            [
                'name'=>  'Applicants Company.Create New Company',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Create New Individual',
                'type'=>'add',
                'permission_list_id' => $applicantsCompanyList->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //
        $individualProfileGeneral = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Individual Profile:General'
        ]);
        //individual profile general
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Individual Profile:General.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $individualProfileGeneral->id
            ],
            [
                'name'=>  'Individual Profile:General.Edit',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Edit',
                'type'=>'edit',
                'permission_list_id' => $individualProfileGeneral->id
            ],
            [
                'name'=>  'Individual Profile:General.Account Manager',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Account Manager',
                'type'=>'info',
                'permission_list_id' => $individualProfileGeneral->id
            ],
            [
                'name'=>  'Individual Profile:General.Change Member Company',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Change Member Company',
                'type'=>'info',
                'permission_list_id' => $individualProfileGeneral->id
            ],
            [
                'name'=>  'Individual Profile:General.Labels',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Labels',
                'type'=>'info',
                'permission_list_id' => $individualProfileGeneral->id
            ],
            [
                'name'=>  'Individual Profile:General.Internal Notes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Internal Notes',
                'type'=>'info',
                'permission_list_id' => $individualProfileGeneral->id
            ],
            [
                'name'=>  'Individual Profile:General.Matched Companies',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Matched Companies',
                'type'=>'info',
                'permission_list_id' => $individualProfileGeneral->id
            ],
            [
                'name'=>  'Individual Profile:General.Risk Level',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Risk Level',
                'type'=>'info',
                'permission_list_id' => $individualProfileGeneral->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //
        $individualProfileSettings = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Individual Profile:Settings'
        ]);
        $individualProfileSession = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Individual Profile:Active Session'
        ]);
        $individualProfileLog = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Individual Profile:Authentication Log'
        ]);
        $companyProfileGeneral = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Company Profile:General'
        ]);
        $companyProfileSettings = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Company Profile:Settings'
        ]);
        $companyProfileSession = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Company Profile:Active Session'
        ]);
        $companyProfileLog = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Company Profile:Authentication Log'
        ]);
        //Settings module
        $rolesList = PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Roles List'
        ]);
        $rolesSettings = PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Roles Settings'
        ]);
        $paymentSystemList = PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Payment System List'
        ]);

        $settingsContact= PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Settings:Contact Details',
            'type'=> 'individual'
        ]);
        $settingsLog= PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Settings:Log in Details',
            'type'=> 'individual'
        ]);
        $settingsSecurity= PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Settings:Security Level',
            'type'=> 'individual'
        ]);
        $settingsAccounts= PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Settings:Accounts',
            'type'=> 'individual'
        ]);
        //Admin module
        $memberCompanyList = PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Member Company List'
        ]);
        $memberCompanyProfile= PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Member Company Profile'
        ]);
        $membersList = PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Members List'
        ]);
        $memberProfileGeneral= PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Member Profile:General'
        ]);
        $memberProfileSettings= PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Member Profile:Settings'
        ]);
        $logsSession= PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Logs:Active Session'
        ]);
        $logsAuth= PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Logs:Authentication Log'
        ]);
        $logsActivity= PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Logs:Activity Log'
        ]);
        $emailTemplatesSettings= PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Email Templates:Settings'
        ]);
        $emailTemplatesNotifications= PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Email Templates:Notifications'
        ]);
        $emailTemplatesTagA= PermissionsList::create([
            'permission_group_id'=>$permissionAdminModule->id,
            'name'=>'Email Templates:Tag'
        ]);
        //Banking module
        $accountList= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Account List'
        ]);
        $accountDetails= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Account Details'
        ]);
        $openAccount= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Open Account'
        ]);
        $makePayments= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'makePayments'
        ]);
        $requisites= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Requisites'
        ]);
        $statements= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Statements'
        ]);
        $paymentList= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Payment List'
        ]);
        $paymentDetails= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Payment Details'
        ]);
        $tickets= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Tickets'
        ]);
        $paymentProviderList= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Payment Provider List'
        ]);
        $paymentProviderSettings= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Payment Provider Settings'
        ]);
        $commissionTemplateList= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Commission Template List'
        ]);
        $commissionTemplateLimits= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Commission Template Limits'
        ]);
        $commissionPriceList= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Commission Price List'
        ]);
        $emailTemplatesTag= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Email Templates:Tag'
        ]);
        $dashboard= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'Dashboard',
            'type'=> 'individual'
        ]);
        $netWorth= PermissionsList::create([
            'permission_group_id'=>$permissionBankModule->id,
            'name'=>'My Net Worth',
            'type'=> 'individual'
        ]);

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

