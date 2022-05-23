<?php

use App\Enums\GuardEnum;
use App\Models\PermissionCategory;
use App\Models\Permissions;
use App\Models\PermissionsList;
use Illuminate\Database\Migrations\Migration;

class AddIndividualSectionsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'Banking Module' => [
                'Dashboard' => [
                    [
                        'display_name' => 'Yes',
                        'name' => 'Dashboard.Yes',
                        'type' => 'add',
                    ],
                    [
                        'display_name' => 'Feedback',
                        'name' => 'Dashboard.Feedback',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Invite Friends',
                        'name' => 'Dashboard.Invite Friends',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Last Payments',
                        'name' => 'Dashboard.Last Payments',
                        'type' => 'info',
                    ],
                ],
                'My Net Worth' => [
                    [
                        'display_name' => 'Yes',
                        'name' => 'My Net Worth.Yes',
                        'type' => 'add',
                    ],
                    [
                        'display_name' => 'Summary',
                        'name' => 'My Net Worth.Summary',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Assets',
                        'name' => 'My Net Worth.Assets',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Liabilities',
                        'name' => 'My Net Worth.Liabilities',
                        'type' => 'info',
                    ],
                ],
                'makePayments' => [
                    [
                        'display_name' => 'Yes',
                        'name' => 'makePayments.Yes',
                        'type' => 'add',
                    ],
                ],
                'Requisites' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Requisites.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Download Requisites',
                        'name' => 'Requisites.Download Requisites',
                        'type' => 'export',
                    ],
                    [
                        'display_name' => 'Send Requisites Details',
                        'name' => 'Requisites.Send Requisites Details',
                        'type' => 'export',
                    ],
                ],
                'My Templates' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'My Templates.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'My Templates.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Delete',
                        'name' => 'My Templates.Delete',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Add New',
                        'name' => 'My Templates.Add New',
                        'type' => 'add',
                    ],
                ],
                'Statements' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Statements.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Export Statement',
                        'name' => 'Statements.Export Statement',
                        'type' => 'export',
                    ],
                ],
                'Payment List' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Payment List.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Payment List.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Cancel Payment',
                        'name' => 'Payment List.Cancel Payment',
                        'type' => 'important',
                    ],
                ],
                'Payment Details' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Payment Details.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Export Payment Details',
                        'name' => 'Payment Details.Export Payment Details',
                        'type' => 'export',
                    ],
                ],
                'Tickets' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Tickets.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'New Ticket',
                        'name' => 'Tickets.New Ticket',
                        'type' => 'add',
                    ],
                    [
                        'display_name' => 'Close Ticket',
                        'name' => 'Tickets.Close Ticket',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Status:Reply Required',
                        'name' => 'Tickets.Status:Reply Required',
                        'type' => 'required',
                    ],
                    [
                        'display_name' => 'Status: Opened',
                        'name' => 'Tickets.Status: Opened',
                        'type' => 'add',
                    ],
                    [
                        'display_name' => 'Status:Closed',
                        'name' => 'Tickets.Status:Closed',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Status:No reply Required',
                        'name' => 'Tickets.Status:No reply Required',
                        'type' => 'no_required',
                    ],
                ],
                'Settings:Contact Details' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Settings:Contact Details.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Settings:Contact Details.Edit',
                        'type' => 'edit',
                    ],
                ],
                'Settings:Log in Details' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Settings:Log in Details.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Settings:Log in Details.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'My Device',
                        'name' => 'Settings:Log in Details.My Device',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Two Factor Authentication',
                        'name' => 'Settings:Log in Details.Two Factor Authentication',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Secure Pin',
                        'name' => 'Settings:Log in Details.Secure Pin',
                        'type' => 'info',
                    ],

                ],
                'Settings:Security Level' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Settings:Security Level.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Settings:Security Level.Edit',
                        'type' => 'edit',
                    ],
                ],
                'Settings:Accounts' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Settings:Accounts.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Settings:Accounts.Edit',
                        'type' => 'edit',
                    ],
                ],
            ]
        ];

        foreach ($permissions as $moduleName => $permissionsListNames) {
            $module = PermissionCategory::whereName($moduleName)->first();

            foreach ($permissionsListNames as $listName => $permissions) {
                $list = PermissionsList::firstOrCreate([
                    'type' => 'individual',
                    'permission_group_id' => $module->id,
                    'name' => $listName,
                ]);

                foreach ($permissions as $p) {
                    $p['guard_name'] = GuardEnum::GUARD_NAME;
                    $p['permission_list_id'] = $list->id;
                    Permissions::create($p);
                }
            }
        }

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
