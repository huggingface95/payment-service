<?php

use App\Enums\GuardEnum;
use App\Models\PermissionCategory;
use App\Models\Permissions;
use App\Models\PermissionsList;
use Illuminate\Database\Migrations\Migration;

class AddSectionsTypeMemberPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'Administration Module' => [
                'Member Company List' => [
                        [
                            'display_name' => 'Read',
                            'name' => 'Member Company List.Read',
                            'type' => 'read',
                        ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Member Company List.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Delete',
                        'name' => 'Member Company List.Delete',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Add New',
                        'name' => 'Member Company List.Add New',
                        'type' => 'add',
                    ],
                ],
                'Member Company Profile' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Member Company Profile.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Member Company Profile.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Business Info',
                        'name' => 'Member Company Profile.Business Info',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Branding',
                        'name' => 'Member Company Profile.Branding',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Departments',
                        'name' => 'Member Company Profile.Departments',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Add New Department',
                        'name' => 'Member Company Profile.Add New Department',
                        'type' => 'add',
                    ],
                ],
                'Members List' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Members List.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Members List.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Add New',
                        'name' => 'Members List.Add New',
                        'type' => 'add',
                    ],
                ],
                'Member Profile:General' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Member Profile:General.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Member Profile:General.Edit',
                        'type' => 'edit',
                    ],
                ],
                'Member Profile:Settings' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Member Profile:Settings.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Member Profile:Settings.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Group/Role Settings',
                        'name' => 'Member Profile:Settings.Group/Role Settings',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Access Limitation',
                        'name' => 'Member Profile:Settings.Access Limitation',
                        'type' => 'info',
                    ],
                ],
                'Logs:Active Session' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Logs:Active Session.Read',
                        'type' => 'read',
                    ],
                ],
                'Logs:Authentication Log' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Logs:Authentication Log.Read',
                        'type' => 'read',
                    ],
                ],
                'Logs:Activity Log' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Logs:Activity Log.Read',
                        'type' => 'read',
                    ],
                ],
            ],
            'Banking Module' => [
                'Account List' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Account List.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Account List.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Export',
                        'name' => 'Account List.Export',
                        'type' => 'export',
                    ],
                ],
                'Account Details' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Account Details.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Account Details.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Show Balance',
                        'name' => 'Account Details.Show Balance',
                        'type' => 'add',
                    ],
                    [
                        'display_name' => 'Show Provider Info',
                        'name' => 'Account Details.Show Provider Info',
                        'type' => 'info',
                    ],
                ],
                'Open Account' => [
                    [
                        'display_name' => 'Yes',
                        'name' => 'Open Account.Yes',
                        'type' => 'add',
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
                        'display_name' => 'Status Reply Required',
                        'name' => 'Tickets.Status Reply Required',
                        'type' => 'required',
                    ],
                    [
                        'display_name' => 'Status Opened',
                        'name' => 'Tickets.Status Opened',
                        'type' => 'add',
                    ],
                    [
                        'display_name' => 'Status Closed',
                        'name' => 'Tickets.Status Closed',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Status No replay Required',
                        'name' => 'Tickets.Status No replay Required',
                        'type' => 'no_required',
                    ],
                ],
                'Payment Provider List' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Payment Provider List.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Payment Provider List.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Delete',
                        'name' => 'Payment Provider List.Delete',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Add New',
                        'name' => 'Payment Provider List.Add New',
                        'type' => 'add',
                    ],
                ],
                'Payment Provider Settings' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Payment Provider Settings.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Payment Provider Settings.Edit',
                        'type' => 'edit',
                    ],
                ],
                'Commission Template List' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Commission Template List.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Commission Template List.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Delete',
                        'name' => 'Commission Template List.Delete',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Add New',
                        'name' => 'Commission Template List.Add New',
                        'type' => 'add',
                    ],
                ],
                'Commission Template Limits' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Commission Template Limits.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Commission Template Limits.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Delete',
                        'name' => 'Commission Template Limits.Delete',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Add New',
                        'name' => 'Commission Template Limits.Add New',
                        'type' => 'add',
                    ],
                ],
                'Commission Price List' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Commission Price List.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Commission Price List.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Delete',
                        'name' => 'Commission Price List.Delete',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Add New',
                        'name' => 'Commission Price List.Add New',
                        'type' => 'add',
                    ],
                ],
            ]
        ];

        foreach ($permissions as $moduleName => $permissionsListNames) {
            $module = PermissionCategory::whereName($moduleName)->first();

            foreach ($permissionsListNames as $listName => $permissions) {
                $list = PermissionsList::whereName($listName)->where('type', 'member')->where('permission_group_id', $module->id)->first();
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
