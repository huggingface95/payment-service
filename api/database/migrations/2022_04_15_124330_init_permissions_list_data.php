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

        //Individual profile Settings
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Individual Profile:Settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $individualProfileSettings->id
            ],
            [
                'name'=>  'Individual Profile:Settings.Edit',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Edit',
                'type'=>'edit',
                'permission_list_id' => $individualProfileSettings->id
            ],
            [
                'name'=>  'Individual Profile:Settings.Role settings',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Group/Role Settings',
                'type'=>'info',
                'permission_list_id' => $individualProfileSettings->id
            ],
            [
                'name'=>  'Individual Profile:Settings.Phone Confirmation',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Phone Confirmation',
                'type'=>'info',
                'permission_list_id' => $individualProfileSettings->id
            ],
            [
                'name'=>  'Individual Profile:Settings.Access Limitation',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Access Limitation',
                'type'=>'info',
                'permission_list_id' => $individualProfileSettings->id
            ],
            [
                'name'=>  'Individual Profile:Settings.Add Banking Module',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Add Banking Module',
                'type'=>'info',
                'permission_list_id' => $individualProfileSettings->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

        $individualProfileSession = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Individual Profile:Active Session'
        ]);

        //Individual Profile Session
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Individual Profile:Active Session.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $individualProfileSession->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //
        $individualProfileLog = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Individual Profile:Authentication Log'
        ]);

        //Individual Profile Log
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Individual Profile:Authentication Log.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $individualProfileLog->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

        $companyProfileGeneral = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Company Profile:General'
        ]);

        //company profile general
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Company Profile:General.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $companyProfileGeneral->id
            ],
            [
                'name'=>  'Company Profile:General.Edit',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Edit',
                'type'=>'edit',
                'permission_list_id' => $companyProfileGeneral->id
            ],
            [
                'name'=>  'Company Profile:General.Account Manager',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Account Manager',
                'type'=>'info',
                'permission_list_id' => $companyProfileGeneral->id
            ],
            [
                'name'=>  'Individual Profile:General.Change Member Company',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Change Member Company',
                'type'=>'info',
                'permission_list_id' => $individualProfileGeneral->id
            ],
            [
                'name'=>  'Company Profile:General.Labels',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Labels',
                'type'=>'info',
                'permission_list_id' => $companyProfileGeneral->id
            ],
            [
                'name'=>  'Company Profile:General.Internal Notes',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Internal Notes',
                'type'=>'info',
                'permission_list_id' => $companyProfileGeneral->id
            ],
            [
                'name'=>  'Company Profile:General.Matched Companies',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Matched Companies',
                'type'=>'info',
                'permission_list_id' => $companyProfileGeneral->id
            ],
            [
                'name'=>  'Company Profile:General.Risk Level',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Risk Level',
                'type'=>'info',
                'permission_list_id' => $companyProfileGeneral->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

        $companyProfileSettings = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Company Profile:Settings'
        ]);
        //Company profile settings
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Company Profile:Settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $companyProfileSettings->id
            ],
            [
                'name'=>  'Company Profile:Settings.Edit',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Edit',
                'type'=>'edit',
                'permission_list_id' => $companyProfileSettings->id
            ],
            [
                'name'=>  'Company Profile:Settings.Banking Access',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Banking Access(User&Rights)',
                'type'=>'info',
                'permission_list_id' => $companyProfileSettings->id
            ],
            [
                'name'=>  'Company Profile:Settings.Phone Confirmation',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Phone Confirmation',
                'type'=>'info',
                'permission_list_id' => $companyProfileSettings->id
            ],
            [
                'name'=>  'Company Profile:Settings.Access Limitation',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Access Limitation',
                'type'=>'info',
                'permission_list_id' => $companyProfileSettings->id
            ],
            [
                'name'=>  'Company Profile:Settings.Add Banking Module',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Add Banking Module',
                'type'=>'info',
                'permission_list_id' => $companyProfileSettings->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

        $companyProfileSession = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Company Profile:Active Session'
        ]);
        //Company active session
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Company Profile:Active Session.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $companyProfileSession->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //
        $companyProfileLog = PermissionsList::create([
            'permission_group_id'=>$permissionManagementModule->id,
            'name'=>'Company Profile:Authentication Log'
        ]);
        //Company profile log
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Company Profile:Authentication Log.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $companyProfileLog->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

        //Settings module
        $rolesList = PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Roles List'
        ]);
        //Role list
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Role list.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $rolesList->id
            ],
            [
                'name'=>  'Role list.Edit',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Edit',
                'type'=>'edit',
                'permission_list_id' => $rolesList->id
            ],
            [
                'name'=>  'Role list.Delete',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Delete',
                'type'=>'important',
                'permission_list_id' => $rolesList->id
            ],
            [
                'name'=>  'Role list.Add new',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Add New',
                'type'=>'add',
                'permission_list_id' => $rolesList->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

        $rolesSettings = PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Roles Settings'
        ]);

        //Role settings
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Roles settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $rolesSettings->id
            ],
            [
                'name'=>  'Roles settings.Edit',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Edit',
                'type'=>'edit',
                'permission_list_id' => $rolesSettings->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

        $groupsList = PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Groups List'
        ]);

        //Group list
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Groups list.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $groupsList->id
            ],
            [
                'name'=>  'Groups list.Edit',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Edit',
                'type'=>'edit',
                'permission_list_id' => $groupsList->id
            ],
            [
                'name'=>  'Groups list.Delete',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Delete',
                'type'=>'important',
                'permission_list_id' => $groupsList->id
            ],
            [
                'name'=>  'Groups list.Add new',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Add New',
                'type'=>'add',
                'permission_list_id' => $groupsList->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

        $groupsSettings = PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Groups Settings'
        ]);

        //Groups Settings
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Groups settings.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $groupsSettings->id
            ],
            [
                'name'=>  'Groups settings.Edit',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Edit',
                'type'=>'edit',
                'permission_list_id' => $groupsSettings->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

        $paymentSystemList = PermissionsList::create([
            'permission_group_id'=>$permissionSettingsModule->id,
            'name'=>'Payment System List'
        ]);

        //Payment system
        $arrayOfPermissions = [];
        $arrayOfPermissions = [
            [
                'name'=>  'Payment System List.Read',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Read',
                'type'=>'read',
                'permission_list_id' => $paymentSystemList->id
            ],
            [
                'name'=>  'Payment System List.Edit',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Edit',
                'type'=>'edit',
                'permission_list_id' => $paymentSystemList->id
            ],
            [
                'name'=>  'Payment System List.Delete',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Delete',
                'type'=>'important',
                'permission_list_id' => $paymentSystemList->id
            ],
            [
                'name'=>  'Payment System List.Add new',
                'guard_name'=> GuardEnum::GUARD_NAME,
                'display_name'=>'Add New',
                'type'=>'add',
                'permission_list_id' => $paymentSystemList->id
            ],
        ];
        Permissions::insert($arrayOfPermissions);
        //

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

