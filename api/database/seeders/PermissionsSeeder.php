<?php

namespace Database\Seeders;

use App\Models\PermissionCategory;
use App\Models\Permissions;
use App\Models\PermissionsList;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $allPermissions = array(
            'Management Module' =>
                array(
                    'data' =>
                        array(
                            'name' => 'Management Module',
                            'is_active' => true,
                        ),
                    'list' =>
                        array(
                            'member' =>
                                array(
                                    '' =>
                                        array(
                                            'Applicants Individual List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Applicants Individual List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Applicants Individual list.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Applicants Individual list.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Applicants Individual list.Export' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Applicants Individual list.Export',
                                                                            'display_name' => 'Export',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                            'Applicants Individual list.Show Banking Info' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Applicants Individual list.Show Banking Info',
                                                                            'display_name' => 'Show Banking Info',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Applicants Individual.Create New Individual' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Applicants Individual.Create New Individual',
                                                                            'display_name' => 'Create New Individual',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Applicants Company List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Applicants Company List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Applicants Company list.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Applicants Company list.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Applicants Company list.Export' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Applicants Company list.Export',
                                                                            'display_name' => 'Export',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                            'Applicants Company list.Show Banking Info' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Applicants Company list.Show Banking Info',
                                                                            'display_name' => 'Show Banking Info',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Applicants Company.Create New Company' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Applicants Company.Create New Company',
                                                                            'display_name' => 'Create New Individual',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Individual Profile:General' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Individual Profile:General',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Individual Profile:General.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:General.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Individual Profile:General.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:General.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Individual Profile:General.Account Manager' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:General.Account Manager',
                                                                            'display_name' => 'Account Manager',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Individual Profile:General.Change Member Company' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:General.Change Member Company',
                                                                            'display_name' => 'Change Member Company',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Individual Profile:General.Labels' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:General.Labels',
                                                                            'display_name' => 'Labels',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Individual Profile:General.Internal Notes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:General.Internal Notes',
                                                                            'display_name' => 'Internal Notes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Individual Profile:General.Matched Companies' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:General.Matched Companies',
                                                                            'display_name' => 'Matched Companies',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Individual Profile:General.Risk Level' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:General.Risk Level',
                                                                            'display_name' => 'Risk Level',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Individual Profile:Settings' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Individual Profile:Settings',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Individual Profile:Settings.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:Settings.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Individual Profile:Settings.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:Settings.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Individual Profile:Settings.Role settings' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:Settings.Role settings',
                                                                            'display_name' => 'Group/Role Settings',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Individual Profile:Settings.Phone Confirmation' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:Settings.Phone Confirmation',
                                                                            'display_name' => 'Phone Confirmation',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Individual Profile:Settings.Access Limitation' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:Settings.Access Limitation',
                                                                            'display_name' => 'Access Limitation',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Individual Profile:Settings.Add Banking Module' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:Settings.Add Banking Module',
                                                                            'display_name' => 'Add Banking Module',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Individual Profile:Active Session' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Individual Profile:Active Session',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Individual Profile:Active Session.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:Active Session.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Individual Profile:Authentication Log' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Individual Profile:Authentication Log',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Individual Profile:Authentication Log.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Individual Profile:Authentication Log.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Company Profile:General' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Company Profile:General',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Company Profile:General.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:General.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Company Profile:General.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:General.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Company Profile:General.Account Manager' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:General.Account Manager',
                                                                            'display_name' => 'Account Manager',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Company Profile:General.Change Member Company' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:General.Change Member Company',
                                                                            'display_name' => 'Change Member Company',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Company Profile:General.Labels' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:General.Labels',
                                                                            'display_name' => 'Labels',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Company Profile:General.Internal Notes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:General.Internal Notes',
                                                                            'display_name' => 'Internal Notes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Company Profile:General.Matched Companies' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:General.Matched Companies',
                                                                            'display_name' => 'Matched Companies',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Company Profile:General.Risk Level' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:General.Risk Level',
                                                                            'display_name' => 'Risk Level',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Company Profile:Settings' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Company Profile:Settings',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Company Profile:Settings.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:Settings.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Company Profile:Settings.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:Settings.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Company Profile:Settings.Banking Access' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:Settings.Banking Access',
                                                                            'display_name' => 'Banking Access(User&Rights)',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Company Profile:Settings.Phone Confirmation' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:Settings.Phone Confirmation',
                                                                            'display_name' => 'Phone Confirmation',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Company Profile:Settings.Access Limitation' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:Settings.Access Limitation',
                                                                            'display_name' => 'Access Limitation',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Company Profile:Settings.Add Banking Module' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:Settings.Add Banking Module',
                                                                            'display_name' => 'Add Banking Module',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Company Profile:Active Session' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Company Profile:Active Session',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Company Profile:Active Session.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:Active Session.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Company Profile:Authentication Log' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Company Profile:Authentication Log',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Company Profile:Authentication Log.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Company Profile:Authentication Log.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                ),
            'Settings Module' =>
                array(
                    'data' =>
                        array(
                            'name' => 'Settings Module',
                            'is_active' => true,
                        ),
                    'list' =>
                        array(
                            'member' =>
                                array(
                                    '' =>
                                        array(
                                            'Roles List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Roles List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Role list.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Role list.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Role list.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Role list.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Role list.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Role list.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Role list.Add new' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Role list.Add new',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Roles Settings' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Roles Settings',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Roles settings.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Roles settings.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Roles settings.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Roles settings.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Groups List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Groups List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Groups list.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Groups list.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Groups list.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Groups list.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Groups list.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Groups list.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Groups list.Add new' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Groups list.Add new',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Groups Settings' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Groups Settings',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Groups settings.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Groups settings.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Groups settings.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Groups settings.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Payment System List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Payment System List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Payment System List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment System List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Payment System List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment System List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Payment System List.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment System List.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Payment System List.Add new' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment System List.Add new',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                ),
            'Administration Module' =>
                array(
                    'data' =>
                        array(
                            'name' => 'Administration Module',
                            'is_active' => true,
                        ),
                    'list' =>
                        array(
                            'member' =>
                                array(
                                    '' =>
                                        array(
                                            'Email Templates:Tag' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Email Templates:Tag',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Email Templates:Tag.Common' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Tag.Common',
                                                                            'display_name' => 'Common',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Email Templates:Tag.System' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Tag.System',
                                                                            'display_name' => 'System',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Email Templates:Tag.Admin Notify' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Tag.Admin Notify',
                                                                            'display_name' => 'Admin Notify',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Email Templates:Settings' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Email Templates:Settings',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Email Templates:Settings.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Settings.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Email Templates:Settings.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Settings.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Email Templates:Settings.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Settings.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Email Templates:Settings.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Settings.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Email Templates:Settings.Type Notification: Admin' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Settings.Type Notification: Admin',
                                                                            'display_name' => 'Type Notification: Admin',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Email Templates:Settings.Type Notification: Applicant' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Settings.Type Notification: Applicant',
                                                                            'display_name' => 'Type Notification: Applicant',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Email Templates:Notifications' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Email Templates:Notifications',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Email Templates:Notifications.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Notifications.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Email Templates:Notifications.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Notifications.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Email Templates:Notifications.Recipient Type:Group' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Notifications.Recipient Type:Group',
                                                                            'display_name' => 'Recipient Type:Group',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Email Templates:Notifications.Recipient Type:Person' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Notifications.Recipient Type:Person',
                                                                            'display_name' => 'Recipient Type:Person',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Email Templates:Notifications.Banking' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Notifications.Banking',
                                                                            'display_name' => 'Banking',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Member Company List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Member Company List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Member Company List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Member Company List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Member Company List.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company List.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Member Company List.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company List.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Member Company Profile' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Member Company Profile',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Member Company Profile.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company Profile.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Member Company Profile.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company Profile.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Member Company Profile.Business Info' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company Profile.Business Info',
                                                                            'display_name' => 'Business Info',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Member Company Profile.Branding' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company Profile.Branding',
                                                                            'display_name' => 'Branding',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Member Company Profile.Departments' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company Profile.Departments',
                                                                            'display_name' => 'Departments',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Member Company Profile.Add New Department' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Company Profile.Add New Department',
                                                                            'display_name' => 'Add New Department',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Members List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Members List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Members List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Members List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Members List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Members List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Members List.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Members List.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Member Profile:General' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Member Profile:General',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Member Profile:General.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Profile:General.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Member Profile:General.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Profile:General.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Member Profile:Settings' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Member Profile:Settings',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Member Profile:Settings.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Profile:Settings.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Member Profile:Settings.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Profile:Settings.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Member Profile:Settings.Group/Role Settings' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Profile:Settings.Group/Role Settings',
                                                                            'display_name' => 'Group/Role Settings',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Member Profile:Settings.Access Limitation' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Member Profile:Settings.Access Limitation',
                                                                            'display_name' => 'Access Limitation',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Logs:Active Session' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Logs:Active Session',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Logs:Active Session.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Logs:Active Session.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Logs:Authentication Log' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Logs:Authentication Log',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Logs:Authentication Log.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Logs:Authentication Log.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Logs:Activity Log' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Logs:Activity Log',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Logs:Activity Log.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Logs:Activity Log.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Email Templates:SMTP Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Email Templates:SMTP Details',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Email Templates:SMTP Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:SMTP Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Email Templates:SMTP Details.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:SMTP Details.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Email Templates:SMTP Details.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:SMTP Details.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Email Templates:SMTP Details.Send test email' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:SMTP Details.Send test email',
                                                                            'display_name' => 'Send test email',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                ),
            'Banking Module' =>
                array(
                    'data' =>
                        array(
                            'name' => 'Banking Module',
                            'is_active' => true,
                        ),
                    'list' =>
                        array(
                            'member' =>
                                array(
                                    '' =>
                                        array(
                                            'Email Templates:Tag' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Email Templates:Tag',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Email Templates:Tag.Banking' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Email Templates:Tag.Banking',
                                                                            'display_name' => 'Banking',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Account List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Account List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Account List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Account List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Account List.Export' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account List.Export',
                                                                            'display_name' => 'Export',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Account Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Account Details',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Account Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Account Details.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Account Details.Show Balance' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Show Balance',
                                                                            'display_name' => 'Show Balance',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Account Details.Show Provider Info' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Show Provider Info',
                                                                            'display_name' => 'Show Provider Info',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Account Details.Limits' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Limits',
                                                                            'display_name' => 'Limits',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Open Account' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Open Account',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Open Account.Yes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Open Account.Yes',
                                                                            'display_name' => 'Yes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'makePayments' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'makePayments',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'makePayments.Yes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'makePayments.Yes',
                                                                            'display_name' => 'Yes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Requisites' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Requisites',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Requisites.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Requisites.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Requisites.Download Requisites' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Requisites.Download Requisites',
                                                                            'display_name' => 'Download Requisites',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                            'Requisites.Send Requisites Details' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Requisites.Send Requisites Details',
                                                                            'display_name' => 'Send Requisites Details',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Statements' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Statements',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Statements.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Statements.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Statements.Export Statement' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Statements.Export Statement',
                                                                            'display_name' => 'Export Statement',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Payment List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Payment List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Payment List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Payment List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Payment List.Cancel Payment' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment List.Cancel Payment',
                                                                            'display_name' => 'Cancel Payment',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Payment Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Payment Details',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Payment Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Payment Details.Export Payment Details' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Details.Export Payment Details',
                                                                            'display_name' => 'Export Payment Details',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Tickets' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Tickets',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Tickets.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Tickets.New Ticket' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.New Ticket',
                                                                            'display_name' => 'New Ticket',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Tickets.Close Ticket' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Close Ticket',
                                                                            'display_name' => 'Close Ticket',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Tickets.Status Reply Required' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status Reply Required',
                                                                            'display_name' => 'Status Reply Required',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'required',
                                                                        ),
                                                                ),
                                                            'Tickets.Status Opened' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status Opened',
                                                                            'display_name' => 'Status Opened',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Tickets.Status Closed' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status Closed',
                                                                            'display_name' => 'Status Closed',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Tickets.Status No replay Required' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status No replay Required',
                                                                            'display_name' => 'Status No replay Required',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'no_required',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Payment Provider List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Payment Provider List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Payment Provider List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Provider List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Payment Provider List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Provider List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Payment Provider List.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Provider List.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Payment Provider List.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Provider List.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Payment Provider Settings' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Payment Provider Settings',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Payment Provider Settings.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Provider Settings.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Payment Provider Settings.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Provider Settings.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Commission Template List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Commission Template List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Commission Template List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Template List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Commission Template List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Template List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Commission Template List.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Template List.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Commission Template List.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Template List.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Commission Template Limits' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Commission Template Limits',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Commission Template Limits.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Template Limits.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Commission Template Limits.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Template Limits.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Commission Template Limits.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Template Limits.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Commission Template Limits.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Template Limits.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Commission Price List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Commission Price List',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Commission Price List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Price List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Commission Price List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Price List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Commission Price List.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Price List.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Commission Price List.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Commission Price List.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Make Payments' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Make Payments',
                                                            'type' => 'member',
                                                            'separator' => NULL,
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Make Payments.Create Payments' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Make Payments.Create Payments',
                                                                            'display_name' => 'Create Payments',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Make Payments.Sign Payments' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Make Payments.Sign Payments',
                                                                            'display_name' => 'Sign Payments',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                        ),
                                ),
                            'individual' =>
                                array(
                                    'business' =>
                                        array(
                                            'Tickets' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Tickets',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Tickets.Close Ticket' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Close Ticket',
                                                                            'display_name' => 'Close Ticket',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Tickets.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Tickets.New Ticket' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.New Ticket',
                                                                            'display_name' => 'New Ticket',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Tickets.Status:Reply Required' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status:Reply Required',
                                                                            'display_name' => 'Status:Reply Required',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'required',
                                                                        ),
                                                                ),
                                                            'Tickets.Status: Opened' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status: Opened',
                                                                            'display_name' => 'Status: Opened',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Tickets.Status:Closed' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status:Closed',
                                                                            'display_name' => 'Status:Closed',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Tickets.Status:No reply Required' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status:No reply Required',
                                                                            'display_name' => 'Status:No reply Required',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'no_required',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Dashboard' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Dashboard',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Dashboard.Yes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Dashboard.Yes',
                                                                            'display_name' => 'Yes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Dashboard.Feedback' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Dashboard.Feedback',
                                                                            'display_name' => 'Feedback',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Dashboard.Invite Friends' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Dashboard.Invite Friends',
                                                                            'display_name' => 'Invite Friends',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Dashboard.Last Payments' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Dashboard.Last Payments',
                                                                            'display_name' => 'Last Payments',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'My Net Worth' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'My Net Worth',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'My Net Worth.Yes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Net Worth.Yes',
                                                                            'display_name' => 'Yes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'My Net Worth.Summary' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Net Worth.Summary',
                                                                            'display_name' => 'Summary',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'My Net Worth.Assets' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Net Worth.Assets',
                                                                            'display_name' => 'Assets',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'My Net Worth.Liabilities' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Net Worth.Liabilities',
                                                                            'display_name' => 'Liabilities',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Account Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Account Details',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Account Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Account Details.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Account Details.Show Balance' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Show Balance',
                                                                            'display_name' => 'Show Balance',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Account Details.Show Provider info' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Show Provider info',
                                                                            'display_name' => 'Show Provider info',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'makePayments' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'makePayments',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'makePayments.Yes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'makePayments.Yes',
                                                                            'display_name' => 'Yes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Requisites' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Requisites',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Requisites.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Requisites.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Requisites.Download Requisites' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Requisites.Download Requisites',
                                                                            'display_name' => 'Download Requisites',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                            'Requisites.Send Requisites Details' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Requisites.Send Requisites Details',
                                                                            'display_name' => 'Send Requisites Details',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'My Templates' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'My Templates',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'My Templates.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Templates.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'My Templates.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Templates.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'My Templates.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Templates.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'My Templates.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Templates.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Statements' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Statements',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Statements.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Statements.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Statements.Export Statement' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Statements.Export Statement',
                                                                            'display_name' => 'Export Statement',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Payment List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Payment List',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Payment List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Payment List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Payment List.Cancel Payment' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment List.Cancel Payment',
                                                                            'display_name' => 'Cancel Payment',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Payment Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Payment Details',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Payment Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Payment Details.Export Payment Details' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Details.Export Payment Details',
                                                                            'display_name' => 'Export Payment Details',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Settings:Contact Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Settings:Contact Details',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Settings:Contact Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Contact Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Settings:Contact Details.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Contact Details.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Settings:Log in Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Settings:Log in Details',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Settings:Log in Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Settings:Log in Details.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Settings:Log in Details.My Device' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.My Device',
                                                                            'display_name' => 'My Device',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Settings:Log in Details.Two Factor Authentication' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.Two Factor Authentication',
                                                                            'display_name' => 'Two Factor Authentication',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Settings:Log in Details.Secure Pin' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.Secure Pin',
                                                                            'display_name' => 'Secure Pin',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Settings:Security Level' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Settings:Security Level',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Settings:Security Level.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Security Level.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Settings:Security Level.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Security Level.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Settings:Accounts' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Settings:Accounts',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Settings:Accounts.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Accounts.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Settings:Accounts.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Accounts.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Make Payments' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Make Payments',
                                                            'type' => 'individual',
                                                            'separator' => 'business',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Make Payments.Create Payments' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Make Payments.Create Payments',
                                                                            'display_name' => 'Create Payments',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Make Payments.Sign Payments' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Make Payments.Sign Payments',
                                                                            'display_name' => 'Sign Payments',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                        ),
                                    'private' =>
                                        array(
                                            'Dashboard' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Dashboard',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Dashboard.Yes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Dashboard.Yes',
                                                                            'display_name' => 'Yes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Dashboard.Feedback' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Dashboard.Feedback',
                                                                            'display_name' => 'Feedback',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Dashboard.Invite Friends' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Dashboard.Invite Friends',
                                                                            'display_name' => 'Invite Friends',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Dashboard.Last Payments' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Dashboard.Last Payments',
                                                                            'display_name' => 'Last Payments',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'My Net Worth' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'My Net Worth',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'My Net Worth.Yes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Net Worth.Yes',
                                                                            'display_name' => 'Yes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'My Net Worth.Summary' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Net Worth.Summary',
                                                                            'display_name' => 'Summary',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'My Net Worth.Assets' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Net Worth.Assets',
                                                                            'display_name' => 'Assets',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'My Net Worth.Liabilities' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Net Worth.Liabilities',
                                                                            'display_name' => 'Liabilities',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Account Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Account Details',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Account Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Account Details.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Account Details.Show Balance' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Show Balance',
                                                                            'display_name' => 'Show Balance',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Account Details.Show Provider info' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Account Details.Show Provider info',
                                                                            'display_name' => 'Show Provider info',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'makePayments' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'makePayments',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'makePayments.Yes' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'makePayments.Yes',
                                                                            'display_name' => 'Yes',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Requisites' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Requisites',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Requisites.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Requisites.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Requisites.Download Requisites' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Requisites.Download Requisites',
                                                                            'display_name' => 'Download Requisites',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                            'Requisites.Send Requisites Details' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Requisites.Send Requisites Details',
                                                                            'display_name' => 'Send Requisites Details',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'My Templates' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'My Templates',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'My Templates.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Templates.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'My Templates.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Templates.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'My Templates.Delete' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Templates.Delete',
                                                                            'display_name' => 'Delete',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'My Templates.Add New' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'My Templates.Add New',
                                                                            'display_name' => 'Add New',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Statements' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Statements',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Statements.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Statements.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Statements.Export Statement' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Statements.Export Statement',
                                                                            'display_name' => 'Export Statement',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Payment List' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Payment List',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Payment List.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment List.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Payment List.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment List.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Payment List.Cancel Payment' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment List.Cancel Payment',
                                                                            'display_name' => 'Cancel Payment',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Payment Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Payment Details',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Payment Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Payment Details.Export Payment Details' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Payment Details.Export Payment Details',
                                                                            'display_name' => 'Export Payment Details',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'export',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Tickets' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Tickets',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Tickets.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Tickets.New Ticket' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.New Ticket',
                                                                            'display_name' => 'New Ticket',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Tickets.Close Ticket' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Close Ticket',
                                                                            'display_name' => 'Close Ticket',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Tickets.Status:Reply Required' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status:Reply Required',
                                                                            'display_name' => 'Status:Reply Required',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'required',
                                                                        ),
                                                                ),
                                                            'Tickets.Status: Opened' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status: Opened',
                                                                            'display_name' => 'Status: Opened',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Tickets.Status:Closed' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status:Closed',
                                                                            'display_name' => 'Status:Closed',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'important',
                                                                        ),
                                                                ),
                                                            'Tickets.Status:No reply Required' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Tickets.Status:No reply Required',
                                                                            'display_name' => 'Status:No reply Required',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'no_required',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Settings:Contact Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Settings:Contact Details',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Settings:Contact Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Contact Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Settings:Contact Details.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Contact Details.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Settings:Log in Details' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Settings:Log in Details',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Settings:Log in Details.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Settings:Log in Details.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                            'Settings:Log in Details.My Device' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.My Device',
                                                                            'display_name' => 'My Device',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Settings:Log in Details.Two Factor Authentication' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.Two Factor Authentication',
                                                                            'display_name' => 'Two Factor Authentication',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                            'Settings:Log in Details.Secure Pin' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Log in Details.Secure Pin',
                                                                            'display_name' => 'Secure Pin',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'info',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Settings:Security Level' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Settings:Security Level',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Settings:Security Level.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Security Level.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Settings:Security Level.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Security Level.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Settings:Accounts' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Settings:Accounts',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Settings:Accounts.Read' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Accounts.Read',
                                                                            'display_name' => 'Read',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'read',
                                                                        ),
                                                                ),
                                                            'Settings:Accounts.Edit' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Settings:Accounts.Edit',
                                                                            'display_name' => 'Edit',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'edit',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                            'Make Payments' =>
                                                array(
                                                    'data' =>
                                                        array(
                                                            'name' => 'Make Payments',
                                                            'type' => 'individual',
                                                            'separator' => 'private',
                                                        ),
                                                    'list' =>
                                                        array(
                                                            'Make Payments.Create Payments' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Make Payments.Create Payments',
                                                                            'display_name' => 'Create Payments',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                            'Make Payments.Sign Payments' =>
                                                                array(
                                                                    'data' =>
                                                                        array(
                                                                            'name' => 'Make Payments.Sign Payments',
                                                                            'display_name' => 'Sign Payments',
                                                                            'guard_name' => 'api',
                                                                            'order' => NULL,
                                                                            'type' => 'add',
                                                                        ),
                                                                ),
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                ),
        );

        foreach ($allPermissions as $moduleKey => $moduleValue) {
            $category = PermissionCategory::firstOrCreate($moduleValue['data']);
            foreach ($moduleValue['list'] as $type => $listValue) {
                foreach ($listValue as $lists) {
                    foreach ($lists as $list) {
                        $list['data']['permission_group_id'] = $category->id;
                        $l = PermissionsList::firstOrCreate($list['data']);
                        foreach ($list['list'] as $permission) {
                            $permission['data']['permission_list_id'] = $l->id;
                            Permissions::firstOrCreate($permission['data']);
                        }
                    }
                }
            }
        }

//        $operations = [
//            'GetApplicantTableSelects' => [
//                'data' => ['referer' => 'applicants/individual/list'],
//                'binds' => ['Applicants Individual list.Read'],
//            ],
//            'GetIndividualsList' => [
//                'data' => ['referer' => 'applicants/individual/list'],
//                'binds' => ['Applicants Individual list.Read'],
//            ],
//            'getAllIndividualCompanies' => [
//                'data' => ['referer' => 'applicants/individual/list'],
//                'binds' => ['Applicants Individual list.Read'],
//            ],
//            'GetIndividual' => [
//                'data' => ['referer' => 'applicants/new/individual/profile/general'],
//                'binds' => ['Applicants Individual.Create New Individual'],
//                'parents' => ['Applicants Individual list.Read'],
//            ],
//            'CreateIndividual' => [
//                'data' => ['referer' => 'applicants/new/individual/profile/general'],
//                'binds' => ['Applicants Individual.Create New Individual'],
//                'parents' => ['Applicants Individual list.Read'],
//            ],
//            'GetApplicantCompanyTableSelects' => [
//                'data' => ['referer' => 'applicants/company/list'],
//                'binds' => ['Applicants Company list.Read'],
//            ],
//            'getAllIndividualCompanies' => [
//                'data' => ['referer' => 'applicants/company/list'],
//                'binds' => ['Applicants Company list.Read'],
//            ],
//            'GetApplicantCompanyList' => [
//                'data' => ['referer' => 'applicants/company/list'],
//                'binds' => ['Applicants Company list.Read'],
//            ],
////            'CreateIndividual' => [
////                'data' => ['referer' => 'applicants/new/company/profile/general'],
////                'binds' => ['Applicants Company.Create New Company'],
////                'parents' => ['Applicants Company list.Read'],
////            ],
//            'GetCompanyMembers' => [
//                'data' => ['referer' => 'applicants/new/company/profile/general'],
//                'binds' => ['Applicants Company.Create New Company'],
//                'parents' => ['Applicants Company list.Read'],
//            ],
//            'GetCompany' => [
//                'data' => ['referer' => 'applicants/new/company/profile/general'],
//                'binds' => ['Applicants Company.Create New Company'],
//                'parents' => ['Applicants Company list.Read'],
//            ],
//
//            'GetCompany' => [
//                'data' => ['referer' => 'applicants/new/company/profile/general'],
//                'binds' => ['Applicants Company.Create New Company'],
//                'parents' => ['Applicants Company list.Read'],
//            ],
//
//
//
//
//        ];
//
//        //verjum vecra globalner@
//
//        $esim = array(
//            0 => 'getMemberData',
//            1 => 'GetMemberByField',
//            2 => 'GetFilterFieldsProfileData',
//            3 => 'GetMemberFullName',
//            4 => 'GetMemberCoverData',
//            5 => 'GetMemberInfoForm',
//            6 => 'getMember',
//            7 => 'GetMember2FaData',
//            8 => 'GetMemberTfaStatus',
//            9 => 'ChangeMemberPassword',
//            10 => 'GetMemberSettingsInfoForm',
//            11 => 'UpdateMemberQuery',
//        );


    }
}
