<?php

namespace Database\Seeders;

use App\Models\PermissionCategory;
use App\Models\PermissionFilter;
use App\Models\PermissionOperation;
use App\Models\Permissions;
use App\Models\PermissionsList;
use Exception;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
        $allPermissions = [
            'KYC Management Module' => [
                'data' => [
                    'name' => 'KYC Management Module',
                    'is_active' => true,
                    'order' => 1,
                ],
                'list' => [
                    'member' => [
                        '' => [
                            'Applicants Individual List' => [
                                'data' => [
                                    'name' => 'Applicants Individual List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 1,
                                ],
                                'list' => [
                                    'Applicants Individual list.Read' => [
                                        'data' => [
                                            'name' => 'Applicants Individual list.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Applicants Individual list.Export' => [
                                        'data' => [
                                            'name' => 'Applicants Individual list.Export',
                                            'display_name' => 'Export',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                    'Applicants Individual list.Show Banking Info' => [
                                        'data' => [
                                            'name' => 'Applicants Individual list.Show Banking Info',
                                            'display_name' => 'Show Banking Info',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Applicants Individual.Create New Individual' => [
                                        'data' => [
                                            'name' => 'Applicants Individual.Create New Individual',
                                            'display_name' => 'Create New Individual',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Applicants Company List' => [
                                'data' => [
                                    'name' => 'Applicants Company List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 2,
                                ],
                                'list' => [
                                    'Applicants Company list.Read' => [
                                        'data' => [
                                            'name' => 'Applicants Company list.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Applicants Company list.Export' => [
                                        'data' => [
                                            'name' => 'Applicants Company list.Export',
                                            'display_name' => 'Export',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                    'Applicants Company list.Show Banking Info' => [
                                        'data' => [
                                            'name' => 'Applicants Company list.Show Banking Info',
                                            'display_name' => 'Show Banking Info',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Applicants Company.Create New Company' => [
                                        'data' => [
                                            'name' => 'Applicants Company.Create New Company',
                                            'display_name' => 'Create New Individual',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Profile:General' => [
                                'data' => [
                                    'name' => 'Individual Profile:General',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 3,
                                ],
                                'list' => [
                                    'Individual Profile:General.Read' => [
                                        'data' => [
                                            'name' => 'Individual Profile:General.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Individual Profile:General.Edit' => [
                                        'data' => [
                                            'name' => 'Individual Profile:General.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Individual Profile:General.Account Manager' => [
                                        'data' => [
                                            'name' => 'Individual Profile:General.Account Manager',
                                            'display_name' => 'Account Manager',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Profile:General.Change Member Company' => [
                                        'data' => [
                                            'name' => 'Individual Profile:General.Change Member Company',
                                            'display_name' => 'Change Member Company',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Profile:General.Labels' => [
                                        'data' => [
                                            'name' => 'Individual Profile:General.Labels',
                                            'display_name' => 'Labels',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Profile:General.Internal Notes' => [
                                        'data' => [
                                            'name' => 'Individual Profile:General.Internal Notes',
                                            'display_name' => 'Internal Notes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Profile:General.Matched Companies' => [
                                        'data' => [
                                            'name' => 'Individual Profile:General.Matched Companies',
                                            'display_name' => 'Matched Companies',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Profile:General.Risk Level' => [
                                        'data' => [
                                            'name' => 'Individual Profile:General.Risk Level',
                                            'display_name' => 'Risk Level',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Profile:Settings' => [
                                'data' => [
                                    'name' => 'Individual Profile:Settings',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 4,
                                ],
                                'list' => [
                                    'Individual Profile:Settings.Read' => [
                                        'data' => [
                                            'name' => 'Individual Profile:Settings.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Individual Profile:Settings.Edit' => [
                                        'data' => [
                                            'name' => 'Individual Profile:Settings.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Individual Profile:Settings.Role settings' => [
                                        'data' => [
                                            'name' => 'Individual Profile:Settings.Role settings',
                                            'display_name' => 'Group/Role Settings',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Profile:Settings.Phone Confirmation' => [
                                        'data' => [
                                            'name' => 'Individual Profile:Settings.Phone Confirmation',
                                            'display_name' => 'Phone Confirmation',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Profile:Settings.Access Limitation' => [
                                        'data' => [
                                            'name' => 'Individual Profile:Settings.Access Limitation',
                                            'display_name' => 'Access Limitation',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Profile:Settings.Add Banking Module' => [
                                        'data' => [
                                            'name' => 'Individual Profile:Settings.Add Banking Module',
                                            'display_name' => 'Add Banking Module',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Profile:Active Session' => [
                                'data' => [
                                    'name' => 'Individual Profile:Active Session',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 5,
                                ],
                                'list' => [
                                    'Individual Profile:Active Session.Read' => [
                                        'data' => [
                                            'name' => 'Individual Profile:Active Session.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Profile:Authentication Log' => [
                                'data' => [
                                    'name' => 'Individual Profile:Authentication Log',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 6,
                                ],
                                'list' => [
                                    'Individual Profile:Authentication Log.Read' => [
                                        'data' => [
                                            'name' => 'Individual Profile:Authentication Log.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Company Profile:General' => [
                                'data' => [
                                    'name' => 'Company Profile:General',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 7,
                                ],
                                'list' => [
                                    'Company Profile:General.Read' => [
                                        'data' => [
                                            'name' => 'Company Profile:General.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Company Profile:General.Edit' => [
                                        'data' => [
                                            'name' => 'Company Profile:General.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Company Profile:General.Account Manager' => [
                                        'data' => [
                                            'name' => 'Company Profile:General.Account Manager',
                                            'display_name' => 'Account Manager',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Company Profile:General.Change Member Company' => [
                                        'data' => [
                                            'name' => 'Company Profile:General.Change Member Company',
                                            'display_name' => 'Change Member Company',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Company Profile:General.Labels' => [
                                        'data' => [
                                            'name' => 'Company Profile:General.Labels',
                                            'display_name' => 'Labels',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Company Profile:General.Internal Notes' => [
                                        'data' => [
                                            'name' => 'Company Profile:General.Internal Notes',
                                            'display_name' => 'Internal Notes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Company Profile:General.Matched Companies' => [
                                        'data' => [
                                            'name' => 'Company Profile:General.Matched Companies',
                                            'display_name' => 'Matched Companies',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Company Profile:General.Risk Level' => [
                                        'data' => [
                                            'name' => 'Company Profile:General.Risk Level',
                                            'display_name' => 'Risk Level',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Company Profile:Settings' => [
                                'data' => [
                                    'name' => 'Company Profile:Settings',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 8,
                                ],
                                'list' => [
                                    'Company Profile:Settings.Read' => [
                                        'data' => [
                                            'name' => 'Company Profile:Settings.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Company Profile:Settings.Edit' => [
                                        'data' => [
                                            'name' => 'Company Profile:Settings.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Company Profile:Settings.Banking Access' => [
                                        'data' => [
                                            'name' => 'Company Profile:Settings.Banking Access',
                                            'display_name' => 'Banking Access(User&Rights)',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Company Profile:Settings.Phone Confirmation' => [
                                        'data' => [
                                            'name' => 'Company Profile:Settings.Phone Confirmation',
                                            'display_name' => 'Phone Confirmation',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Company Profile:Settings.Access Limitation' => [
                                        'data' => [
                                            'name' => 'Company Profile:Settings.Access Limitation',
                                            'display_name' => 'Access Limitation',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Company Profile:Settings.Add Banking Module' => [
                                        'data' => [
                                            'name' => 'Company Profile:Settings.Add Banking Module',
                                            'display_name' => 'Add Banking Module',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Company Profile:Active Session' => [
                                'data' => [
                                    'name' => 'Company Profile:Active Session',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 9,
                                ],
                                'list' => [
                                    'Company Profile:Active Session.Read' => [
                                        'data' => [
                                            'name' => 'Company Profile:Active Session.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Company Profile:Authentication Log' => [
                                'data' => [
                                    'name' => 'Company Profile:Authentication Log',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 10,
                                ],
                                'list' => [
                                    'Company Profile:Authentication Log.Read' => [
                                        'data' => [
                                            'name' => 'Company Profile:Authentication Log.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'Settings Module' => [
                'data' => [
                    'name' => 'Settings Module',
                    'is_active' => true,
                    'order' => 2,
                ],
                'list' => [
                    'member' => [
                        '' => [
                            'Roles List' => [
                                'data' => [
                                    'name' => 'Roles List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 1,
                                ],
                                'list' => [
                                    'Role list.Read' => [
                                        'data' => [
                                            'name' => 'Role list.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Role list.Add new' => [
                                        'data' => [
                                            'name' => 'Role list.Add new',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Role list.Show users list' => [
                                        'data' => [
                                            'name' => 'Role list.Show users list',
                                            'display_name' => 'Show users list',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Roles Settings' => [
                                'data' => [
                                    'name' => 'Roles Settings',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 2,
                                ],
                                'list' => [
                                    'Roles settings.Read' => [
                                        'data' => [
                                            'name' => 'Roles settings.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Roles settings.Edit' => [
                                        'data' => [
                                            'name' => 'Roles settings.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Roles settings.Delete' => [
                                        'data' => [
                                            'name' => 'Roles settings.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                ],
                            ],
                            'GroupType List' => [
                                'data' => [
                                    'name' => 'GroupType List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 3,
                                ],
                                'list' => [
                                    'GroupType list.Read' => [
                                        'data' => [
                                            'name' => 'GroupType list.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'GroupType list.Add new' => [
                                        'data' => [
                                            'name' => 'GroupType list.Add new',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'GroupType Settings' => [
                                'data' => [
                                    'name' => 'GroupType Settings',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 4,
                                ],
                                'list' => [
                                    'GroupType settings.Read' => [
                                        'data' => [
                                            'name' => 'GroupType settings.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'GroupType settings.Edit' => [
                                        'data' => [
                                            'name' => 'GroupType settings.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'GroupType settings.Delete' => [
                                        'data' => [
                                            'name' => 'GroupType settings.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                ],
                            ],
                            'Payment System List' => [
                                'data' => [
                                    'name' => 'Payment System List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 5,
                                ],
                                'list' => [
                                    'Payment System List.Read' => [
                                        'data' => [
                                            'name' => 'Payment System List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Payment System List.Edit' => [
                                        'data' => [
                                            'name' => 'Payment System List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Payment System List.Delete' => [
                                        'data' => [
                                            'name' => 'Payment System List.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Payment System List.Add new' => [
                                        'data' => [
                                            'name' => 'Payment System List.Add new',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'Administration Module' => [
                'data' => [
                    'name' => 'Administration Module',
                    'is_active' => true,
                    'order' => 3,
                ],
                'list' => [
                    'member' => [
                        '' => [
                            'Email Templates:Tag' => [
                                'data' => [
                                    'name' => 'Email Templates:Tag',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 10,
                                ],
                                'list' => [
                                    'Email Templates:Tag.Common' => [
                                        'data' => [
                                            'name' => 'Email Templates:Tag.Common',
                                            'display_name' => 'Common',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Email Templates:Tag.System' => [
                                        'data' => [
                                            'name' => 'Email Templates:Tag.System',
                                            'display_name' => 'System',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Email Templates:Tag.Admin Notify' => [
                                        'data' => [
                                            'name' => 'Email Templates:Tag.Admin Notify',
                                            'display_name' => 'Admin Notify',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Email Templates:Tag.Banking' => [
                                        'data' => [
                                            'name' => 'Email Templates:Tag.Banking',
                                            'display_name' => 'Banking',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Email Templates:Settings' => [
                                'data' => [
                                    'name' => 'Email Templates:Settings',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 11,
                                ],
                                'list' => [
                                    'Email Templates:Settings.Read' => [
                                        'data' => [
                                            'name' => 'Email Templates:Settings.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Email Templates:Settings.Edit' => [
                                        'data' => [
                                            'name' => 'Email Templates:Settings.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Email Templates:Settings.Delete' => [
                                        'data' => [
                                            'name' => 'Email Templates:Settings.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                            'is_super_admin' => true,
                                        ],
                                    ],
                                    'Email Templates:Settings.Add New' => [
                                        'data' => [
                                            'name' => 'Email Templates:Settings.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                            'is_super_admin' => true,
                                        ],
                                    ],
                                    'Email Templates:Settings.Type Notification: Admin' => [
                                        'data' => [
                                            'name' => 'Email Templates:Settings.Type Notification: Admin',
                                            'display_name' => 'Type Notification: Admin',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Email Templates:Settings.Type Notification: Applicant' => [
                                        'data' => [
                                            'name' => 'Email Templates:Settings.Type Notification: Applicant',
                                            'display_name' => 'Type Notification: Applicant',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Super Admin Email Template.Create' => [
                                        'data' => [
                                            'name' => 'Super Admin Email Template.Create',
                                            'display_name' => 'Create',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                            'is_super_admin' => true,
                                        ],
                                    ],
                                ],
                            ],
                            'Email Templates:Notifications' => [
                                'data' => [
                                    'name' => 'Email Templates:Notifications',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 12,
                                ],
                                'list' => [
                                    'Email Templates:Notifications.Read' => [
                                        'data' => [
                                            'name' => 'Email Templates:Notifications.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Email Templates:Notifications.Edit' => [
                                        'data' => [
                                            'name' => 'Email Templates:Notifications.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Email Templates:Notifications.Recipient Type:Group' => [
                                        'data' => [
                                            'name' => 'Email Templates:Notifications.Recipient Type:Group',
                                            'display_name' => 'Recipient Type:Group',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Email Templates:Notifications.Recipient Type:Person' => [
                                        'data' => [
                                            'name' => 'Email Templates:Notifications.Recipient Type:Person',
                                            'display_name' => 'Recipient Type:Person',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Email Templates:Notifications.Banking' => [
                                        'data' => [
                                            'name' => 'Email Templates:Notifications.Banking',
                                            'display_name' => 'Banking',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Member Company List' => [
                                'data' => [
                                    'name' => 'Member Company List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 1,
                                ],
                                'list' => [
                                    'Member Company List.Read' => [
                                        'data' => [
                                            'name' => 'Member Company List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Member Company List.Edit' => [
                                        'data' => [
                                            'name' => 'Member Company List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Member Company List.Delete' => [
                                        'data' => [
                                            'name' => 'Member Company List.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Member Company List.Add New' => [
                                        'data' => [
                                            'name' => 'Member Company List.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Member Company Profile' => [
                                'data' => [
                                    'name' => 'Member Company Profile',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 2,
                                ],
                                'list' => [
                                    'Member Company Profile.Read' => [
                                        'data' => [
                                            'name' => 'Member Company Profile.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Member Company Profile.Edit' => [
                                        'data' => [
                                            'name' => 'Member Company Profile.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Member Company Profile.Business Info' => [
                                        'data' => [
                                            'name' => 'Member Company Profile.Business Info',
                                            'display_name' => 'Business Info',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Member Company Profile.Branding' => [
                                        'data' => [
                                            'name' => 'Member Company Profile.Branding',
                                            'display_name' => 'Branding',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Member Company Profile.Departments' => [
                                        'data' => [
                                            'name' => 'Member Company Profile.Departments',
                                            'display_name' => 'Departments',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Member Company Profile.Add New Department' => [
                                        'data' => [
                                            'name' => 'Member Company Profile.Add New Department',
                                            'display_name' => 'Add New Department',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Members List' => [
                                'data' => [
                                    'name' => 'Members List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 3,
                                ],
                                'list' => [
                                    'Members List.Read' => [
                                        'data' => [
                                            'name' => 'Members List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Members List.Edit' => [
                                        'data' => [
                                            'name' => 'Members List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Members List.Add New' => [
                                        'data' => [
                                            'name' => 'Members List.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Member Profile:General' => [
                                'data' => [
                                    'name' => 'Member Profile:General',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 4,
                                ],
                                'list' => [
                                    'Member Profile:General.Read' => [
                                        'data' => [
                                            'name' => 'Member Profile:General.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Member Profile:General.Edit' => [
                                        'data' => [
                                            'name' => 'Member Profile:General.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                ],
                            ],
                            'Member Profile:Settings' => [
                                'data' => [
                                    'name' => 'Member Profile:Settings',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 5,
                                ],
                                'list' => [
                                    'Member Profile:Settings.Read' => [
                                        'data' => [
                                            'name' => 'Member Profile:Settings.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Member Profile:Settings.Edit' => [
                                        'data' => [
                                            'name' => 'Member Profile:Settings.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Member Profile:Settings.Group/Role Settings' => [
                                        'data' => [
                                            'name' => 'Member Profile:Settings.Group/Role Settings',
                                            'display_name' => 'Group/Role Settings',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Member Profile:Settings.Access Limitation' => [
                                        'data' => [
                                            'name' => 'Member Profile:Settings.Access Limitation',
                                            'display_name' => 'Access Limitation',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Logs:Active Session' => [
                                'data' => [
                                    'name' => 'Logs:Active Session',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 7,
                                ],
                                'list' => [
                                    'Logs:Active Session.Read' => [
                                        'data' => [
                                            'name' => 'Logs:Active Session.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Logs:Authentication Log' => [
                                'data' => [
                                    'name' => 'Logs:Authentication Log',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 8,
                                ],
                                'list' => [
                                    'Logs:Authentication Log.Read' => [
                                        'data' => [
                                            'name' => 'Logs:Authentication Log.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Logs:Activity Log' => [
                                'data' => [
                                    'name' => 'Logs:Activity Log',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 9,
                                ],
                                'list' => [
                                    'Logs:Activity Log.Read' => [
                                        'data' => [
                                            'name' => 'Logs:Activity Log.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Email Templates:SMTP Details' => [
                                'data' => [
                                    'name' => 'Email Templates:SMTP Details',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 13,
                                ],
                                'list' => [
                                    'Email Templates:SMTP Details.Read' => [
                                        'data' => [
                                            'name' => 'Email Templates:SMTP Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Email Templates:SMTP Details.Edit' => [
                                        'data' => [
                                            'name' => 'Email Templates:SMTP Details.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Email Templates:SMTP Details.Add New' => [
                                        'data' => [
                                            'name' => 'Email Templates:SMTP Details.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Email Templates:SMTP Details.Send test email' => [
                                        'data' => [
                                            'name' => 'Email Templates:SMTP Details.Send test email',
                                            'display_name' => 'Send test email',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Member: Security' => [
                                'data' => [
                                    'name' => 'Member: Security',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 6,
                                ],
                                'list' => [
                                    'Member: Security.Read' => [
                                        'data' => [
                                            'name' => 'Member: Security.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Member: Security.Edit' => [
                                        'data' => [
                                            'name' => 'Member: Security.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Member: Security.Security Settings' => [
                                        'data' => [
                                            'name' => 'Member: Security.Security Settings',
                                            'display_name' => 'Security Settings',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'Banking Module' => [
                'data' => [
                    'name' => 'Banking Module',
                    'is_active' => true,
                    'order' => 4,
                ],
                'list' => [
                    'member' => [
                        '' => [
                            'Email Templates:Tag' => [
                                'data' => [
                                    'name' => 'Email Templates:Tag',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 16,
                                ],
                                'list' => [
                                    'Email Templates:Tag.Banking' => [
                                        'data' => [
                                            'name' => 'Email Templates:Tag.Banking',
                                            'display_name' => 'Banking',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Account List' => [
                                'data' => [
                                    'name' => 'Account List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 1,
                                ],
                                'list' => [
                                    'Account List.Read' => [
                                        'data' => [
                                            'name' => 'Account List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Account List.Edit' => [
                                        'data' => [
                                            'name' => 'Account List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Account List.Export' => [
                                        'data' => [
                                            'name' => 'Account List.Export',
                                            'display_name' => 'Export',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'Account Details' => [
                                'data' => [
                                    'name' => 'Account Details',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 2,
                                ],
                                'list' => [
                                    'Account Details.Read' => [
                                        'data' => [
                                            'name' => 'Account Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Account Details.Edit' => [
                                        'data' => [
                                            'name' => 'Account Details.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Account Details.Show Balance' => [
                                        'data' => [
                                            'name' => 'Account Details.Show Balance',
                                            'display_name' => 'Show Balance',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Account Details.Show Provider Info' => [
                                        'data' => [
                                            'name' => 'Account Details.Show Provider Info',
                                            'display_name' => 'Show Provider Info',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Limits' => [
                                        'data' => [
                                            'name' => 'Account Details.Limits',
                                            'display_name' => 'Limits',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Pending' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Pending',
                                            'display_name' => 'Status: Pending',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Active' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Active',
                                            'display_name' => 'Status: Active',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Waiting for IBAN Activation' => [
                                        'data' => [
                                            'name' => 'Account Details.Waiting for IBAN Activation',
                                            'display_name' => 'Waiting for IBAN Activation',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Closed' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Closed',
                                            'display_name' => 'Status: Closed',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Suspended' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Suspended',
                                            'display_name' => 'Status: Suspended',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Open Account' => [
                                'data' => [
                                    'name' => 'Open Account',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 3,
                                ],
                                'list' => [
                                    'Open Account.Yes' => [
                                        'data' => [
                                            'name' => 'Open Account.Yes',
                                            'display_name' => 'Yes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'makePayments' => [
                                'data' => [
                                    'name' => 'makePayments',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 4,
                                ],
                                'list' => [
                                    'makePayments.Yes' => [
                                        'data' => [
                                            'name' => 'makePayments.Yes',
                                            'display_name' => 'Yes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Requisites' => [
                                'data' => [
                                    'name' => 'Requisites',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 5,
                                ],
                                'list' => [
                                    'Requisites.Read' => [
                                        'data' => [
                                            'name' => 'Requisites.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Requisites.Download Requisites' => [
                                        'data' => [
                                            'name' => 'Requisites.Download Requisites',
                                            'display_name' => 'Download Requisites',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                    'Requisites.Send Requisites Details' => [
                                        'data' => [
                                            'name' => 'Requisites.Send Requisites Details',
                                            'display_name' => 'Send Requisites Details',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'Statements' => [
                                'data' => [
                                    'name' => 'Statements',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 6,
                                ],
                                'list' => [
                                    'Statements.Read' => [
                                        'data' => [
                                            'name' => 'Statements.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Statements.Export Statement' => [
                                        'data' => [
                                            'name' => 'Statements.Export Statement',
                                            'display_name' => 'Export Statement',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'Payment List' => [
                                'data' => [
                                    'name' => 'Payment List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 7,
                                ],
                                'list' => [
                                    'Payment List.Read' => [
                                        'data' => [
                                            'name' => 'Payment List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Payment List.Edit' => [
                                        'data' => [
                                            'name' => 'Payment List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Payment List.Cancel Payment' => [
                                        'data' => [
                                            'name' => 'Payment List.Cancel Payment',
                                            'display_name' => 'Cancel Payment',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                ],
                            ],
                            'Payment Details' => [
                                'data' => [
                                    'name' => 'Payment Details',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 8,
                                ],
                                'list' => [
                                    'Payment Details.Read' => [
                                        'data' => [
                                            'name' => 'Payment Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Payment Details.Export Payment Details' => [
                                        'data' => [
                                            'name' => 'Payment Details.Export Payment Details',
                                            'display_name' => 'Export Payment Details',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'Tickets' => [
                                'data' => [
                                    'name' => 'Tickets',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 9,
                                ],
                                'list' => [
                                    'Tickets.Read' => [
                                        'data' => [
                                            'name' => 'Tickets.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Tickets.New Ticket' => [
                                        'data' => [
                                            'name' => 'Tickets.New Ticket',
                                            'display_name' => 'New Ticket',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Tickets.Close Ticket' => [
                                        'data' => [
                                            'name' => 'Tickets.Close Ticket',
                                            'display_name' => 'Close Ticket',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Tickets.Status Reply Required' => [
                                        'data' => [
                                            'name' => 'Tickets.Status Reply Required',
                                            'display_name' => 'Status Reply Required',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'required',
                                        ],
                                    ],
                                    'Tickets.Status Opened' => [
                                        'data' => [
                                            'name' => 'Tickets.Status Opened',
                                            'display_name' => 'Status Opened',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Tickets.Status Closed' => [
                                        'data' => [
                                            'name' => 'Tickets.Status Closed',
                                            'display_name' => 'Status Closed',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Tickets.Status No replay Required' => [
                                        'data' => [
                                            'name' => 'Tickets.Status No replay Required',
                                            'display_name' => 'Status No replay Required',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'no_required',
                                        ],
                                    ],
                                ],
                            ],
                            'Payment Provider List' => [
                                'data' => [
                                    'name' => 'Payment Provider List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 10,
                                ],
                                'list' => [
                                    'Payment Provider List.Read' => [
                                        'data' => [
                                            'name' => 'Payment Provider List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Payment Provider List.Edit' => [
                                        'data' => [
                                            'name' => 'Payment Provider List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Payment Provider List.Delete' => [
                                        'data' => [
                                            'name' => 'Payment Provider List.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Payment Provider List.Add New' => [
                                        'data' => [
                                            'name' => 'Payment Provider List.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Payment Provider Settings' => [
                                'data' => [
                                    'name' => 'Payment Provider Settings',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 11,
                                ],
                                'list' => [
                                    'Payment Provider Settings.Read' => [
                                        'data' => [
                                            'name' => 'Payment Provider Settings.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Payment Provider Settings.Edit' => [
                                        'data' => [
                                            'name' => 'Payment Provider Settings.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                ],
                            ],
                            'Commission Template List' => [
                                'data' => [
                                    'name' => 'Commission Template List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 12,
                                ],
                                'list' => [
                                    'Commission Template List.Read' => [
                                        'data' => [
                                            'name' => 'Commission Template List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Commission Template List.Edit' => [
                                        'data' => [
                                            'name' => 'Commission Template List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Commission Template List.Delete' => [
                                        'data' => [
                                            'name' => 'Commission Template List.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Commission Template List.Add New' => [
                                        'data' => [
                                            'name' => 'Commission Template List.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Commission Template Limits' => [
                                'data' => [
                                    'name' => 'Commission Template Limits',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 13,
                                ],
                                'list' => [
                                    'Commission Template Limits.Read' => [
                                        'data' => [
                                            'name' => 'Commission Template Limits.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Commission Template Limits.Edit' => [
                                        'data' => [
                                            'name' => 'Commission Template Limits.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Commission Template Limits.Delete' => [
                                        'data' => [
                                            'name' => 'Commission Template Limits.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Commission Template Limits.Add New' => [
                                        'data' => [
                                            'name' => 'Commission Template Limits.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Commission Price List' => [
                                'data' => [
                                    'name' => 'Commission Price List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 14,
                                ],
                                'list' => [
                                    'Commission Price List.Read' => [
                                        'data' => [
                                            'name' => 'Commission Price List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Commission Price List.Edit' => [
                                        'data' => [
                                            'name' => 'Commission Price List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Commission Price List.Delete' => [
                                        'data' => [
                                            'name' => 'Commission Price List.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Commission Price List.Add New' => [
                                        'data' => [
                                            'name' => 'Commission Price List.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Make Payments' => [
                                'data' => [
                                    'name' => 'Make Payments',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 15,
                                ],
                                'list' => [
                                    'Make Payments.Create Payments' => [
                                        'data' => [
                                            'name' => 'Make Payments.Create Payments',
                                            'display_name' => 'Create Payments',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Make Payments.Sign Payments' => [
                                        'data' => [
                                            'name' => 'Make Payments.Sign Payments',
                                            'display_name' => 'Sign Payments',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'individual' => [
                        'business' => [
                            'Tickets' => [
                                'data' => [
                                    'name' => 'Tickets',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 1,
                                ],
                                'list' => [
                                    'Tickets.Close Ticket' => [
                                        'data' => [
                                            'name' => 'Tickets.Close Ticket',
                                            'display_name' => 'Close Ticket',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Tickets.Read' => [
                                        'data' => [
                                            'name' => 'Tickets.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Tickets.New Ticket' => [
                                        'data' => [
                                            'name' => 'Tickets.New Ticket',
                                            'display_name' => 'New Ticket',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Tickets.Status:Reply Required' => [
                                        'data' => [
                                            'name' => 'Tickets.Status:Reply Required',
                                            'display_name' => 'Status:Reply Required',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'required',
                                        ],
                                    ],
                                    'Tickets.Status: Opened' => [
                                        'data' => [
                                            'name' => 'Tickets.Status: Opened',
                                            'display_name' => 'Status: Opened',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Tickets.Status:Closed' => [
                                        'data' => [
                                            'name' => 'Tickets.Status:Closed',
                                            'display_name' => 'Status:Closed',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Tickets.Status:No reply Required' => [
                                        'data' => [
                                            'name' => 'Tickets.Status:No reply Required',
                                            'display_name' => 'Status:No reply Required',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'no_required',
                                        ],
                                    ],
                                ],
                            ],
                            'Dashboard' => [
                                'data' => [
                                    'name' => 'Dashboard',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 2,
                                ],
                                'list' => [
                                    'Dashboard.Yes' => [
                                        'data' => [
                                            'name' => 'Dashboard.Yes',
                                            'display_name' => 'Yes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Dashboard.Feedback' => [
                                        'data' => [
                                            'name' => 'Dashboard.Feedback',
                                            'display_name' => 'Feedback',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Dashboard.Invite Friends' => [
                                        'data' => [
                                            'name' => 'Dashboard.Invite Friends',
                                            'display_name' => 'Invite Friends',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Dashboard.Last Payments' => [
                                        'data' => [
                                            'name' => 'Dashboard.Last Payments',
                                            'display_name' => 'Last Payments',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'My Net Worth' => [
                                'data' => [
                                    'name' => 'My Net Worth',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 3,
                                ],
                                'list' => [
                                    'My Net Worth.Yes' => [
                                        'data' => [
                                            'name' => 'My Net Worth.Yes',
                                            'display_name' => 'Yes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'My Net Worth.Summary' => [
                                        'data' => [
                                            'name' => 'My Net Worth.Summary',
                                            'display_name' => 'Summary',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'My Net Worth.Assets' => [
                                        'data' => [
                                            'name' => 'My Net Worth.Assets',
                                            'display_name' => 'Assets',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'My Net Worth.Liabilities' => [
                                        'data' => [
                                            'name' => 'My Net Worth.Liabilities',
                                            'display_name' => 'Liabilities',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Account Details' => [
                                'data' => [
                                    'name' => 'Account Details',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 4,
                                ],
                                'list' => [
                                    'Account Details.Read' => [
                                        'data' => [
                                            'name' => 'Account Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Account Details.Edit' => [
                                        'data' => [
                                            'name' => 'Account Details.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Account Details.Show Balance' => [
                                        'data' => [
                                            'name' => 'Account Details.Show Balance',
                                            'display_name' => 'Show Balance',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Account Details.Show Provider info' => [
                                        'data' => [
                                            'name' => 'Account Details.Show Provider info',
                                            'display_name' => 'Show Provider info',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Pending' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Pending',
                                            'display_name' => 'Status: Pending',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Active' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Active',
                                            'display_name' => 'Status: Active',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Waiting for IBAN Activation' => [
                                        'data' => [
                                            'name' => 'Account Details.Waiting for IBAN Activation',
                                            'display_name' => 'Waiting for IBAN Activation',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Closed' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Closed',
                                            'display_name' => 'Status: Closed',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Suspended' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Suspended',
                                            'display_name' => 'Status: Suspended',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'makePayments' => [
                                'data' => [
                                    'name' => 'makePayments',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 5,
                                ],
                                'list' => [
                                    'makePayments.Yes' => [
                                        'data' => [
                                            'name' => 'makePayments.Yes',
                                            'display_name' => 'Yes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Requisites' => [
                                'data' => [
                                    'name' => 'Requisites',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 6,
                                ],
                                'list' => [
                                    'Requisites.Read' => [
                                        'data' => [
                                            'name' => 'Requisites.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Requisites.Download Requisites' => [
                                        'data' => [
                                            'name' => 'Requisites.Download Requisites',
                                            'display_name' => 'Download Requisites',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                    'Requisites.Send Requisites Details' => [
                                        'data' => [
                                            'name' => 'Requisites.Send Requisites Details',
                                            'display_name' => 'Send Requisites Details',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'My Templates' => [
                                'data' => [
                                    'name' => 'My Templates',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 7,
                                ],
                                'list' => [
                                    'My Templates.Read' => [
                                        'data' => [
                                            'name' => 'My Templates.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'My Templates.Edit' => [
                                        'data' => [
                                            'name' => 'My Templates.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'My Templates.Delete' => [
                                        'data' => [
                                            'name' => 'My Templates.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'My Templates.Add New' => [
                                        'data' => [
                                            'name' => 'My Templates.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Statements' => [
                                'data' => [
                                    'name' => 'Statements',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 8,
                                ],
                                'list' => [
                                    'Statements.Read' => [
                                        'data' => [
                                            'name' => 'Statements.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Statements.Export Statement' => [
                                        'data' => [
                                            'name' => 'Statements.Export Statement',
                                            'display_name' => 'Export Statement',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'Payment List' => [
                                'data' => [
                                    'name' => 'Payment List',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 9,
                                ],
                                'list' => [
                                    'Payment List.Read' => [
                                        'data' => [
                                            'name' => 'Payment List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Payment List.Edit' => [
                                        'data' => [
                                            'name' => 'Payment List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Payment List.Cancel Payment' => [
                                        'data' => [
                                            'name' => 'Payment List.Cancel Payment',
                                            'display_name' => 'Cancel Payment',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                ],
                            ],
                            'Payment Details' => [
                                'data' => [
                                    'name' => 'Payment Details',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 10,
                                ],
                                'list' => [
                                    'Payment Details.Read' => [
                                        'data' => [
                                            'name' => 'Payment Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Payment Details.Export Payment Details' => [
                                        'data' => [
                                            'name' => 'Payment Details.Export Payment Details',
                                            'display_name' => 'Export Payment Details',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'Settings:Contact Details' => [
                                'data' => [
                                    'name' => 'Settings:Contact Details',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 11,
                                ],
                                'list' => [
                                    'Settings:Contact Details.Read' => [
                                        'data' => [
                                            'name' => 'Settings:Contact Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Settings:Contact Details.Edit' => [
                                        'data' => [
                                            'name' => 'Settings:Contact Details.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                ],
                            ],
                            'Settings:Log in Details' => [
                                'data' => [
                                    'name' => 'Settings:Log in Details',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 12,
                                ],
                                'list' => [
                                    'Settings:Log in Details.Read' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Settings:Log in Details.Edit' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Settings:Log in Details.My Device' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.My Device',
                                            'display_name' => 'My Device',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Settings:Log in Details.Two Factor Authentication' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.Two Factor Authentication',
                                            'display_name' => 'Two Factor Authentication',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Settings:Log in Details.Secure Pin' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.Secure Pin',
                                            'display_name' => 'Secure Pin',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Settings:Security Level' => [
                                'data' => [
                                    'name' => 'Settings:Security Level',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 13,
                                ],
                                'list' => [
                                    'Settings:Security Level.Read' => [
                                        'data' => [
                                            'name' => 'Settings:Security Level.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Settings:Security Level.Edit' => [
                                        'data' => [
                                            'name' => 'Settings:Security Level.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                ],
                            ],
                            'Settings:Account' => [
                                'data' => [
                                    'name' => 'Settings:Account',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 14,
                                ],
                                'list' => [
                                    'Settings:Account.Read' => [
                                        'data' => [
                                            'name' => 'Settings:Account.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Settings:Account.Edit' => [
                                        'data' => [
                                            'name' => 'Settings:Account.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                ],
                            ],
                            'Make Payments' => [
                                'data' => [
                                    'name' => 'Make Payments',
                                    'type' => 'applicant',
                                    'separator' => 'business',
                                    'order' => 15,
                                ],
                                'list' => [
                                    'Make Payments.Create Payments' => [
                                        'data' => [
                                            'name' => 'Make Payments.Create Payments',
                                            'display_name' => 'Create Payments',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Make Payments.Sign Payments' => [
                                        'data' => [
                                            'name' => 'Make Payments.Sign Payments',
                                            'display_name' => 'Sign Payments',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'private' => [
                            'Dashboard' => [
                                'data' => [
                                    'name' => 'Dashboard',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 1,
                                ],
                                'list' => [
                                    'Dashboard.Yes' => [
                                        'data' => [
                                            'name' => 'Dashboard.Yes',
                                            'display_name' => 'Yes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Dashboard.Feedback' => [
                                        'data' => [
                                            'name' => 'Dashboard.Feedback',
                                            'display_name' => 'Feedback',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Dashboard.Invite Friends' => [
                                        'data' => [
                                            'name' => 'Dashboard.Invite Friends',
                                            'display_name' => 'Invite Friends',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Dashboard.Last Payments' => [
                                        'data' => [
                                            'name' => 'Dashboard.Last Payments',
                                            'display_name' => 'Last Payments',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'My Net Worth' => [
                                'data' => [
                                    'name' => 'My Net Worth',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 2,
                                ],
                                'list' => [
                                    'My Net Worth.Yes' => [
                                        'data' => [
                                            'name' => 'My Net Worth.Yes',
                                            'display_name' => 'Yes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'My Net Worth.Summary' => [
                                        'data' => [
                                            'name' => 'My Net Worth.Summary',
                                            'display_name' => 'Summary',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'My Net Worth.Assets' => [
                                        'data' => [
                                            'name' => 'My Net Worth.Assets',
                                            'display_name' => 'Assets',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'My Net Worth.Liabilities' => [
                                        'data' => [
                                            'name' => 'My Net Worth.Liabilities',
                                            'display_name' => 'Liabilities',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Account Details' => [
                                'data' => [
                                    'name' => 'Account Details',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 3,
                                ],
                                'list' => [
                                    'Account Details.Read' => [
                                        'data' => [
                                            'name' => 'Account Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Account Details.Edit' => [
                                        'data' => [
                                            'name' => 'Account Details.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Account Details.Show Balance' => [
                                        'data' => [
                                            'name' => 'Account Details.Show Balance',
                                            'display_name' => 'Show Balance',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Account Details.Show Provider info' => [
                                        'data' => [
                                            'name' => 'Account Details.Show Provider info',
                                            'display_name' => 'Show Provider info',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Pending' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Pending',
                                            'display_name' => 'Status: Pending',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Active' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Active',
                                            'display_name' => 'Status: Active',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Waiting for IBAN Activation' => [
                                        'data' => [
                                            'name' => 'Account Details.Waiting for IBAN Activation',
                                            'display_name' => 'Waiting for IBAN Activation',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Closed' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Closed',
                                            'display_name' => 'Status: Closed',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Account Details.Status: Suspended' => [
                                        'data' => [
                                            'name' => 'Account Details.Status: Suspended',
                                            'display_name' => 'Status: Suspended',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'makePayments' => [
                                'data' => [
                                    'name' => 'makePayments',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 4,
                                ],
                                'list' => [
                                    'makePayments.Yes' => [
                                        'data' => [
                                            'name' => 'makePayments.Yes',
                                            'display_name' => 'Yes',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Requisites' => [
                                'data' => [
                                    'name' => 'Requisites',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 5,
                                ],
                                'list' => [
                                    'Requisites.Read' => [
                                        'data' => [
                                            'name' => 'Requisites.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Requisites.Download Requisites' => [
                                        'data' => [
                                            'name' => 'Requisites.Download Requisites',
                                            'display_name' => 'Download Requisites',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                    'Requisites.Send Requisites Details' => [
                                        'data' => [
                                            'name' => 'Requisites.Send Requisites Details',
                                            'display_name' => 'Send Requisites Details',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'My Templates' => [
                                'data' => [
                                    'name' => 'My Templates',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 6,
                                ],
                                'list' => [
                                    'My Templates.Read' => [
                                        'data' => [
                                            'name' => 'My Templates.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'My Templates.Edit' => [
                                        'data' => [
                                            'name' => 'My Templates.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'My Templates.Delete' => [
                                        'data' => [
                                            'name' => 'My Templates.Delete',
                                            'display_name' => 'Delete',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'My Templates.Add New' => [
                                        'data' => [
                                            'name' => 'My Templates.Add New',
                                            'display_name' => 'Add New',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                            'Statements' => [
                                'data' => [
                                    'name' => 'Statements',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 7,
                                ],
                                'list' => [
                                    'Statements.Read' => [
                                        'data' => [
                                            'name' => 'Statements.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Statements.Export Statement' => [
                                        'data' => [
                                            'name' => 'Statements.Export Statement',
                                            'display_name' => 'Export Statement',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'Payment List' => [
                                'data' => [
                                    'name' => 'Payment List',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 8,
                                ],
                                'list' => [
                                    'Payment List.Read' => [
                                        'data' => [
                                            'name' => 'Payment List.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Payment List.Edit' => [
                                        'data' => [
                                            'name' => 'Payment List.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Payment List.Cancel Payment' => [
                                        'data' => [
                                            'name' => 'Payment List.Cancel Payment',
                                            'display_name' => 'Cancel Payment',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                ],
                            ],
                            'Payment Details' => [
                                'data' => [
                                    'name' => 'Payment Details',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 9,
                                ],
                                'list' => [
                                    'Payment Details.Read' => [
                                        'data' => [
                                            'name' => 'Payment Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Payment Details.Export Payment Details' => [
                                        'data' => [
                                            'name' => 'Payment Details.Export Payment Details',
                                            'display_name' => 'Export Payment Details',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'export',
                                        ],
                                    ],
                                ],
                            ],
                            'Tickets' => [
                                'data' => [
                                    'name' => 'Tickets',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 10,
                                ],
                                'list' => [
                                    'Tickets.Read' => [
                                        'data' => [
                                            'name' => 'Tickets.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Tickets.New Ticket' => [
                                        'data' => [
                                            'name' => 'Tickets.New Ticket',
                                            'display_name' => 'New Ticket',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Tickets.Close Ticket' => [
                                        'data' => [
                                            'name' => 'Tickets.Close Ticket',
                                            'display_name' => 'Close Ticket',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Tickets.Status:Reply Required' => [
                                        'data' => [
                                            'name' => 'Tickets.Status:Reply Required',
                                            'display_name' => 'Status:Reply Required',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'required',
                                        ],
                                    ],
                                    'Tickets.Status: Opened' => [
                                        'data' => [
                                            'name' => 'Tickets.Status: Opened',
                                            'display_name' => 'Status: Opened',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Tickets.Status:Closed' => [
                                        'data' => [
                                            'name' => 'Tickets.Status:Closed',
                                            'display_name' => 'Status:Closed',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'important',
                                        ],
                                    ],
                                    'Tickets.Status:No reply Required' => [
                                        'data' => [
                                            'name' => 'Tickets.Status:No reply Required',
                                            'display_name' => 'Status:No reply Required',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'no_required',
                                        ],
                                    ],
                                ],
                            ],
                            'Settings:Contact Details' => [
                                'data' => [
                                    'name' => 'Settings:Contact Details',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 11,
                                ],
                                'list' => [
                                    'Settings:Contact Details.Read' => [
                                        'data' => [
                                            'name' => 'Settings:Contact Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Settings:Contact Details.Edit' => [
                                        'data' => [
                                            'name' => 'Settings:Contact Details.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                ],
                            ],
                            'Settings:Log in Details' => [
                                'data' => [
                                    'name' => 'Settings:Log in Details',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 12,
                                ],
                                'list' => [
                                    'Settings:Log in Details.Read' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Settings:Log in Details.Edit' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Settings:Log in Details.My Device' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.My Device',
                                            'display_name' => 'My Device',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Settings:Log in Details.Two Factor Authentication' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.Two Factor Authentication',
                                            'display_name' => 'Two Factor Authentication',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Settings:Log in Details.Secure Pin' => [
                                        'data' => [
                                            'name' => 'Settings:Log in Details.Secure Pin',
                                            'display_name' => 'Secure Pin',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'info',
                                        ],
                                    ],
                                ],
                            ],
                            'Settings:Security Level' => [
                                'data' => [
                                    'name' => 'Settings:Security Level',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 13,
                                ],
                                'list' => [
                                    'Settings:Security Level.Read' => [
                                        'data' => [
                                            'name' => 'Settings:Security Level.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Settings:Security Level.Edit' => [
                                        'data' => [
                                            'name' => 'Settings:Security Level.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                ],
                            ],
                            'Settings:Account' => [
                                'data' => [
                                    'name' => 'Settings:Account',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 14,
                                ],
                                'list' => [
                                    'Settings:Account.Read' => [
                                        'data' => [
                                            'name' => 'Settings:Account.Read',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'read',
                                        ],
                                    ],
                                    'Settings:Account.Edit' => [
                                        'data' => [
                                            'name' => 'Settings:Account.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'edit',
                                        ],
                                    ],
                                ],
                            ],
                            'Make Payments' => [
                                'data' => [
                                    'name' => 'Make Payments',
                                    'type' => 'applicant',
                                    'separator' => 'private',
                                    'order' => 15,
                                ],
                                'list' => [
                                    'Make Payments.Create Payments' => [
                                        'data' => [
                                            'name' => 'Make Payments.Create Payments',
                                            'display_name' => 'Create Payments',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Make Payments.Sign Payments' => [
                                        'data' => [
                                            'name' => 'Make Payments.Sign Payments',
                                            'display_name' => 'Sign Payments',
                                            'guard_name' => 'api',
                                            'order' => null,
                                            'type' => 'add',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($allPermissions as $moduleValue) {
            $order = $moduleValue['data']['order'];
            unset($moduleValue['data']['order']);
            $category = PermissionCategory::firstOrCreate($moduleValue['data']);
            $category->order = $order;
            $category->save();

            foreach ($moduleValue['list'] as $listValue) {
                foreach ($listValue as $lists) {
                    foreach ($lists as $list) {
                        $uniqueData = [
                            'permission_group_id' => $category->id,
                            'name' => $list['data']['name'],
                            'type' => $list['data']['type'],
                            'separator' => $list['data']['separator'],
                        ];
                        $l = PermissionsList::firstOrCreate($uniqueData);
                        $l->order = $list['data']['order'];
                        $l->save();

                        foreach ($list['list'] as $permission) {
                            $permission['data']['permission_list_id'] = $l->id;
                            $order = $permission['data']['order'];
                            $superAdmin = false;
                            unset($permission['data']['order']);
                            if (isset($permission['data']['is_super_admin'])) {
                                $superAdmin = $permission['data']['is_super_admin'];
                                unset($permission['data']['is_super_admin']);
                            }
                            unset($permission['data']['order']);
                            /** @var Permissions $p */
                            $p = Permissions::firstOrCreate($permission['data']);
                            $p->order = $order;
                            $p->is_super_admin = $superAdmin;
                            $p->save();
                        }
                    }
                }
            }
        }

        $operations = [
            [
                'name' => 'GetApplicantTableSelects',
                'referer' => 'applicants/individual/list',
                'binds' => ['Applicants Individual list.Read'],
            ],
            [
                'name' => 'GetIndividualsList',
                'referer' => 'applicants/individual/list',
                'binds' => ['Applicants Individual list.Read'],
            ],
            [
                'name' => 'getAllIndividualCompanies',
                'referer' => 'applicants/individual/list',
                'binds' => ['Applicants Individual list.Read'],
            ],
            [
                'name' => 'GetIndividual',
                'referer' => 'applicants/new/individual/profile/general',
                'binds' => ['Applicants Individual.Create New Individual'],
                'parents' => ['Applicants Individual list.Read'],
            ],
            [
                'name' => 'CreateIndividual',
                'referer' => 'applicants/new/individual/profile/general',
                'binds' => ['Applicants Individual.Create New Individual'],
                'parents' => ['Applicants Individual list.Read'],
            ],
            [
                'name' => 'checkEmail',
                'referer' => 'applicants/new/individual/profile/general',
                'binds' => ['Applicants Individual.Create New Individual'],
                'parents' => ['Applicants Individual list.Read'],
            ],
            [
                'name' => 'GetApplicantIndividualPhone',
                'referer' => 'applicants/new/individual/profile/general',
                'binds' => ['Applicants Individual.Create New Individual'],
                'parents' => ['Applicants Individual list.Read'],
            ],
            [
                'name' => 'GetIndividualRiskLevel',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Risk Level'],
                'parents' => ['Individual Profile:General.Read', 'Individual Profile:General.Edit'],
            ],
            [
                'name' => 'UpdateApplicantIndividualManager',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Account Manager'],
                'parents' => ['Individual Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantIndividualsData',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Read', 'Individual Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantIndividualManager',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Account Manager'],
                'parents' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'UpdateApplicantIndividualInfo',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantIndividualByGroup',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'UpdateApplicantIndividualProfileData',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit'],
            ],
            [
                'name' => 'UpdateApplicantIndividualContacts',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit'],
            ],
            [
                'name' => 'UpdateApplicantIndividualAddress',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit'],
            ],
            [
                'name' => 'CreateIndividualNote',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Internal Notes'],
                'parents' => ['Individual Profile:General.Edit'],
            ],
            [
                'name' => 'GetIndividualNote',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Internal Notes'],
                'parents' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'CreateIndividualRiskLevel',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Risk Level'],
                'parents' => ['Individual Profile:General.Edit'],
            ],
            [
                'name' => 'GetGroupsListByType',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'GetIndividualLabel',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Labels'],
                'parents' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'GetApplicantIndividualProfileData',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'GetApplicantIndividualAddress',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'GetApplicantIndividualInfo',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'GetApplicantIndividualContacts',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'GetRiskLevel',
                'referer' => 'applicants/individual/full-profile/profile/general',
                'binds' => ['Individual Profile:General.Risk Level'],
                'parents' => ['Individual Profile:General.Edit', 'Individual Profile:General.Read'],
            ],
            [
                'name' => 'GetApplicantIndividualsData',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Read', 'Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyBankingAccess',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Access Limitation'],
                'parents' => ['Individual Profile:Settings.Read', 'Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetIndividual',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Read', 'Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetIndividualModules',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Read', 'Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantIndividualPhone',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Read', 'Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetGroupsListByType',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Read', 'Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantIndividualByGroup',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Read', 'Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'UpdateApplicantIndividualPhone',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Phone Confirmation'],
                'parents' => ['Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'UpdateApplicantIndividualPassword',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Phone Confirmation'],
                'parents' => ['Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'UpdateApplicantIndividualModule',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Add Banking Module'],
                'parents' => ['Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'DeleteIndividualModules',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Add Banking Module'],
                'parents' => ['Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'CreateIndividualModules',
                'referer' => 'applicants/individual/full-profile/profile/settings',
                'binds' => ['Individual Profile:Settings.Add Banking Module'],
                'parents' => ['Individual Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyList',
                'referer' => 'applicants/company/list',
                'binds' => ['Applicants Company list.Read'],
            ],
            [
                'name' => 'getAllIndividualCompanies',
                'referer' => 'applicants/company/list',
                'binds' => ['Applicants Company list.Read'],
            ],
            [
                'name' => 'GetApplicantCompanyTableSelects',
                'referer' => 'applicants/company/list',
                'binds' => ['Applicants Company list.Read'],
            ],
            [
                'name' => 'GetCompany',
                'referer' => 'applicants/new/company/profile/general',
                'binds' => ['Applicants Company.Create New Company'],
                'parents' => ['Applicants Company list.Read'],
            ],
            [
                'name' => 'CreateIndividual',
                'referer' => 'applicants/new/company/profile/general',
                'binds' => ['Applicants Company.Create New Company'],
                'parents' => ['Applicants Company list.Read'],
            ],
            [
                'name' => 'GetIndividualRiskLevel',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Risk Level'],
                'parents' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyProfileData',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyPageData',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyInfo',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyContacts',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyNote',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Internal Notes'],
                'parents' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantMatchedUsers',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetRiskLevel',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Risk Level'],
                'parents' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetCompanyMembers',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyAddress',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyManager',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Account Manager'],
                'parents' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyLabel',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Labels'],
                'parents' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetGroupsListByType',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetApplicantCompaniesData',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Read', 'Company Profile:General.Edit'],
            ],
            [
                'name' => 'UpdateApplicantCompanyInfo',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'CreateApplicantCompanyLabel',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Labels'],
                'parents' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'UpdateApplicantCompanyProfileData',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'UpdateApplicantCompanyInfo',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'UpdateApplicantCompanyContacts',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'CreateApplicantCompanyNote',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Internal Notes'],
                'parents' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'CreateApplicantMatchedUsers',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'DeleteApplicantMatchedUsers',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'UpdateApplicantCompanyAddress',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'CreateIndividualRiskLevel',
                'referer' => 'applicants/company/full-profile/profile/general',
                'binds' => ['Company Profile:General.Risk Level'],
                'parents' => ['Company Profile:General.Edit'],
            ],
            [
                'name' => 'GetCompany',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyPageData',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyModules',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyPhone',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetGroupsListByType',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantCompaniesData',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyBankingAccess',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Banking Access'],
                'parents' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantCompanyPageData',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Banking Access'],
                'parents' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'UpdateApplicantCompany',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'UpdateApplicantCompanyPhone',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Phone Confirmation'],
                'parents' => ['Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'UpdateApplicantCompanyPhone',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Phone Confirmation'],
                'parents' => ['Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetAllCountries',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetApplicantModules',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Access Limitation'],
                'parents' => ['Company Profile:Settings.Read', 'Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'CreateApplicantCompanyModule',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Access Limitation'],
                'parents' => ['Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'UpdateApplicantCompanyModule',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Access Limitation'],
                'parents' => ['Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'DeleteApplicantCompanyModule',
                'referer' => 'applicants/company/full-profile/profile/settings',
                'binds' => ['Company Profile:Settings.Access Limitation'],
                'parents' => ['Company Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetUsers',
                'referer' => 'settings/manager-roles/list',
                'binds' => ['Role list.Read'],
            ],
            [
                'name' => 'GetRolesName',
                'referer' => 'settings/manager-roles/list',
                'binds' => ['Role list.Read'],
            ],
            [
                'name' => 'GetManagerRoleList',
                'referer' => 'settings/manager-roles/list',
                'binds' => ['Role list.Read'],
            ],
            [
                'name' => 'GET_ALL_COMPANIES',
                'referer' => 'settings/manager-roles/list',
                'binds' => ['Role list.Read'],
            ],
            [
                'name' => 'GetRolesByFilter',
                'referer' => 'settings/manager-roles/new',
                'binds' => ['Role list.Add new'],
                'parents' => ['Role list.Read'],
            ],
            [
                'name' => 'GetAllPermissions',
                'referer' => 'settings/manager-roles/new',
                'binds' => ['Role list.Add new'],
                'parents' => ['Role list.Read'],
            ],
            [
                'name' => 'GetGroupsName',
                'referer' => 'settings/manager-roles/new',
                'binds' => ['Role list.Add new'],
                'parents' => ['Role list.Read'],
            ],
            [
                'name' => 'CreateManagerRole',
                'referer' => 'settings/manager-roles/new',
                'binds' => ['Role list.Add new'],
                'parents' => ['Role list.Read'],
            ],
            [
                'name' => 'GetRolesByFilter',
                'referer' => 'settings/manager-roles/edit',
                'binds' => ['Roles settings.Read', 'Roles settings.Edit'],
            ],
            [
                'name' => 'GetAllPermissions',
                'referer' => 'settings/manager-roles/edit',
                'binds' => ['Roles settings.Read', 'Roles settings.Edit'],
            ],
            [
                'name' => 'GetGroupsName',
                'referer' => 'settings/manager-roles/edit',
                'binds' => ['Roles settings.Read', 'Roles settings.Edit'],
            ],
            [
                'name' => 'GET_ALL_COMPANIES',
                'referer' => 'settings/manager-roles/edit',
                'binds' => ['Roles settings.Read', 'Roles settings.Edit'],
            ],
            [
                'name' => 'UpdateManagerRole',
                'referer' => 'settings/manager-roles/edit',
                'binds' => ['Roles settings.Edit'],
            ],
            [
                'name' => 'getGroupSelects',
                'referer' => 'settings/manager-groups/list',
                'binds' => ['GroupType list.Read'],
            ],
            [
                'name' => 'GetGroups',
                'referer' => 'settings/manager-groups/list',
                'binds' => ['GroupType list.Read'],
            ],
            [
                'name' => 'GetGroupsTableSelects',
                'referer' => 'settings/manager-groups/list',
                'binds' => ['GroupType list.Read'],
            ],
            [
                'name' => 'GetRolesByFilter',
                'referer' => 'settings/manager-groups/new-group',
                'binds' => ['GroupType list.Add new'],
                'parents' => ['GroupType list.Read'],
            ],
            [
                'name' => 'getGroupSelects',
                'referer' => 'settings/manager-groups/new-group',
                'binds' => ['GroupType list.Add new'],
                'parents' => ['GroupType list.Read'],
            ],
            [
                'name' => 'CreateGroupSetting',
                'referer' => 'settings/manager-groups/new-group',
                'binds' => ['GroupType list.Add new'],
                'parents' => ['GroupType list.Read'],
            ],
            [
                'name' => 'CheckGroups',
                'referer' => 'settings/manager-groups/new-group',
                'binds' => ['GroupType list.Add new'],
                'parents' => ['GroupType list.Read'],
            ],
            [
                'name' => 'GetRolesByFilter',
                'referer' => 'settings/manager-groups/settings',
                'binds' => ['GroupType settings.Read', 'GroupType settings.Edit'],
            ],
            [
                'name' => 'getGroupSelects',
                'referer' => 'settings/manager-groups/settings',
                'binds' => ['GroupType settings.Read', 'GroupType settings.Edit'],
            ],
            [
                'name' => 'GetGroupsTableSelects',
                'referer' => 'settings/manager-groups/settings',
                'binds' => ['GroupType settings.Read', 'GroupType settings.Edit'],
            ],
            [
                'name' => 'UpdateGroupSetting',
                'referer' => 'settings/manager-groups/settings',
                'binds' => ['GroupType settings.Edit'],
            ],
            [
                'name' => 'CheckGroups',
                'referer' => 'settings/manager-groups/settings',
                'binds' => ['GroupType settings.Edit'],
            ],
            [
                'name' => 'GetPaymentsList',
                'referer' => 'settings/payment-list',
                'binds' => ['Payment System List.Read', 'Payment System List.Edit'],
            ],
            [
                'name' => 'CreatePayment',
                'referer' => 'settings/payment-list',
                'binds' => ['Payment System List.Add new'],
                'parents' => ['Payment System List.Read', 'Payment System List.Edit'],
            ],
            [
                'name' => 'UpdatePayment',
                'referer' => 'settings/payment-list',
                'binds' => ['Payment System List.Edit'],
            ],
            [
                'name' => 'DeletePayment',
                'referer' => 'settings/payment-list',
                'binds' => ['Payment System List.Delete'],
                'parents' => ['Payment System List.Read', 'Payment System List.Edit'],
            ],
            [
                'name' => 'GetEmailTemplatesOnCompanyID',
                'referer' => 'administration/email/email-template-settings',
                'binds' => ['Email Templates:Settings.Read', 'Email Templates:Settings.Edit'],
            ],
            [
                'name' => 'GetCompanies',
                'referer' => 'administration/email/email-template-settings',
                'binds' => ['Email Templates:Settings.Read', 'Email Templates:Settings.Edit'],
            ],
            [
                'name' => 'CreateEmailTemplate',
                'referer' => 'administration/email/email-template-settings',
                'binds' => ['Super Admin Email Template.Create'],
            ],
            [
                'name' => 'GetCompanies',
                'referer' => 'administration/email/email-notifications',
                'binds' => ['Email Templates:Notifications.Read', 'Email Templates:Notifications.Edit'],
            ],
            [
                'name' => 'GetCompanies',
                'referer' => 'administration/email/smtp-details',
                'binds' => ['Email Templates:SMTP Details.Edit', 'Email Templates:SMTP Details.Read'],
            ],
            [
                'name' => 'CreateSMTPTemplate',
                'referer' => 'administration/email/smtp-details',
                'binds' => ['Email Templates:SMTP Details.Add New'],
                'parents' => ['Email Templates:SMTP Details.Edit'],
            ],
            [
                'name' => 'GetSMTPTemplates',
                'referer' => 'administration/email/smtp-details',
                'binds' => ['Email Templates:SMTP Details.Edit', 'Email Templates:SMTP Details.Read'],
            ],
            [
                'name' => 'SendTestTemplateEmail',
                'referer' => 'administration/email/smtp-details',
                'binds' => ['Email Templates:SMTP Details.Send test email'],
                'parents' => ['Email Templates:SMTP Details.Edit', 'Email Templates:SMTP Details.Read'],
            ],
            [
                'name' => 'SendTestTemplateEmail',
                'referer' => 'administration/email/email-template-settings',
                'binds' => ['Email Templates:Settings.Read', 'Email Templates:Settings.Edit'],
            ],
            [
                'name' => 'GetGroupTypes',
                'referer' => 'administration/email/email-notifications',
                'binds' => ['Email Templates:Notifications.Read', 'Email Templates:Notifications.Edit'],
            ],
            [
                'name' => 'GetAdministrationCompanyList',
                'referer' => 'administration/company/list',
                'binds' => ['Member Company List.Read', 'Member Company List.Edit'],
            ],
            [
                'name' => 'DeleteMemberCompanyQuery',
                'referer' => 'administration/company/list',
                'binds' => ['Member Company List.Delete'],
                'parents' => ['Member Company List.Read', 'Member Company List.Edit'],
            ],
            [
                'name' => 'GetAllCountries',
                'referer' => 'administration/company/new-company',
                'binds' => ['Member Company List.Add New'],
                'parents' => ['Member Company List.Read', 'Member Company List.Edit'],
            ],
            [
                'name' => 'CreateMemberCompanyQuery',
                'referer' => 'administration/company/new-company',
                'binds' => ['Member Company List.Add New'],
                'parents' => ['Member Company List.Read', 'Member Company List.Edit'],
            ],
            [
                'name' => 'GetCompanyById',
                'referer' => 'administration/company/full-profile/profile/business-information',
                'binds' => ['Member Company Profile.Business Info'],
                'parents' => ['Member Company Profile.Read', 'Member Company Profile.Edit'],
            ],
            [
                'name' => 'GetCompanyById',
                'referer' => 'administration/company/full-profile/profile/departments',
                'binds' => ['Member Company Profile.Departments'],
                'parents' => ['Member Company Profile.Read', 'Member Company Profile.Edit'],
            ],
            [
                'name' => 'GetCompanyById',
                'referer' => 'administration/company/full-profile/profile/branding',
                'binds' => ['Member Company Profile.Branding'],
                'parents' => ['Member Company Profile.Read', 'Member Company Profile.Edit'],
            ],
            [
                'name' => 'UpdateMemberCompanyQuery',
                'referer' => 'administration/company/full-profile/profile/business-information',
                'binds' => ['Member Company Profile.Business Info'],
                'parents' => ['Member Company Profile.Edit'],
            ],
            [
                'name' => 'GetCompanyDepartmentsList',
                'referer' => 'administration/company/full-profile/profile/departments',
                'binds' => ['Member Company Profile.Departments'],
                'parents' => ['Member Company Profile.Read', 'Member Company Profile.Edit'],
            ],
            [
                'name' => 'CreateDepartmentCompany',
                'referer' => 'administration/company/full-profile/profile/departments',
                'binds' => ['Member Company Profile.Add New Department'],
                'parents' => ['Member Company Profile.Edit'],
            ],
            [
                'name' => 'getMemberCompanies',
                'referer' => 'administration/members/list',
                'binds' => ['Members List.Read', 'Members List.Edit'],
            ],
            [
                'name' => 'GetMembersList',
                'referer' => 'administration/members/list',
                'binds' => ['Members List.Read', 'Members List.Edit'],
            ],
            [
                'name' => 'GetFiltersNewMemberData',
                'referer' => 'administration/members/new-member',
                'binds' => ['Members List.Add New'],
                'parents' => ['Members List.Read', 'Members List.Edit'],
            ],
            [
                'name' => 'CreateMember',
                'referer' => 'administration/members/new-member',
                'binds' => ['Members List.Add New'],
                'parents' => ['Members List.Read', 'Members List.Edit'],
            ],
            [
                'name' => 'GetFilterFieldsProfileData',
                'referer' => 'administration/members/full-profile/profile/general',
                'binds' => ['Member Profile:General.Read', 'Member Profile:General.Edit'],
            ],
            [
                'name' => 'GetCompanyDepartment',
                'referer' => 'administration/members/full-profile/profile/general',
                'binds' => ['Member Profile:General.Read', 'Member Profile:General.Edit'],
            ],
            [
                'name' => 'GetMemberSettingsInfoForm',
                'referer' => 'administration/members/full-profile/profile/general',
                'binds' => ['Member Profile:General.Read', 'Member Profile:General.Edit'],
            ],
            [
                'name' => 'GetMemberInfoForm',
                'referer' => 'administration/members/full-profile/profile/general',
                'binds' => ['Member Profile:General.Read', 'Member Profile:General.Edit'],
            ],
            [
                'name' => 'GetMemberCoverData',
                'referer' => 'administration/members/full-profile/profile/general',
                'binds' => ['Member Profile:General.Read', 'Member Profile:General.Edit'],
            ],
            [
                'name' => 'GetMemberFullName',
                'referer' => 'administration/members/full-profile/profile/general',
                'binds' => ['Member Profile:General.Read', 'Member Profile:General.Edit'],
            ],
            [
                'name' => 'UpdateMemberQuery',
                'referer' => 'administration/members/full-profile/profile/general',
                'binds' => ['Member Profile:General.Edit'],
            ],
            [
                'name' => 'CreateDepartmentCompany',
                'referer' => 'administration/members/full-profile/profile/general',
                'binds' => ['Member Profile:General.Edit'],
            ],
            [
                'name' => 'CreateCompanyDepartmentPosition',
                'referer' => 'administration/members/full-profile/profile/general',
                'binds' => ['Member Profile:General.Edit'],
            ],
            [
                'name' => 'GetFilterFieldsProfileData',
                'referer' => 'administration/members/full-profile/profile/settings',
                'binds' => ['Member Profile:Settings.Read', 'Member Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetMemberInfoForm',
                'referer' => 'administration/members/full-profile/profile/settings',
                'binds' => ['Member Profile:Settings.Read', 'Member Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetMemberSettingsForm',
                'referer' => 'administration/members/full-profile/profile/settings',
                'binds' => ['Member Profile:Settings.Read', 'Member Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetCompanyData',
                'referer' => 'administration/members/full-profile/profile/settings',
                'binds' => ['Member Profile:Settings.Read', 'Member Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetMemberCoverData',
                'referer' => 'administration/members/full-profile/profile/settings',
                'binds' => ['Member Profile:Settings.Read', 'Member Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetMemberFullName',
                'referer' => 'administration/members/full-profile/profile/settings',
                'binds' => ['Member Profile:Settings.Read', 'Member Profile:Settings.Edit'],
            ],
            [
                'name' => 'UpdateMemberQuery',
                'referer' => 'administration/members/full-profile/profile/settings',
                'binds' => ['Member Profile:Settings.Group/Role Settings'],
                'parents' => ['Member Profile:Settings.Edit'],
            ],
            [
                'name' => 'GetCommissionTemplateListOptions',
                'referer' => 'banking/commission-templates/template-list',
                'binds' => ['Commission Template List.Read', 'Commission Template List.Edit'],
            ],
            [
                'name' => 'GetCommissionTemplateList',
                'referer' => 'banking/commission-templates/template-list',
                'binds' => ['Commission Template List.Read', 'Commission Template List.Edit'],
            ],
            [
                'name' => 'GetPaymentProviderListOptions',
                'referer' => 'banking/commission-templates/template-list',
                'binds' => ['Commission Template List.Read', 'Commission Template List.Edit'],
            ],
            [
                'name' => 'SwitchStatusCommissionTemplate',
                'referer' => 'banking/commission-templates/template-list',
                'binds' => ['Commission Template List.Edit'],
            ],
            [
                'name' => 'DeleteCommissionTemplate',
                'referer' => 'banking/commission-templates/template-list',
                'binds' => ['Commission Template List.Delete'],
                'parents' => ['Commission Template List.Read', 'Commission Template List.Edit'],
            ],
            [
                'name' => 'CreateCommissionTemplate',
                'referer' => 'banking/commission-templates/template-list',
                'binds' => ['Commission Template List.Add New'],
                'parents' => ['Commission Template List.Read', 'Commission Template List.Edit'],
            ],
            [
                'name' => 'SetTemplateLimitsCommissionTemplate',
                'referer' => 'banking/commission-templates/template-settings',
                'binds' => ['Commission Template Limits.Read', 'Commission Template Limits.Edit'],
            ],
            [
                'name' => 'CreateCommissionTemplateLimit',
                'referer' => 'banking/commission-templates/template-settings',
                'binds' => ['Commission Template Limits.Add New'],
                'parents' => ['Commission Template Limits.Read', 'Commission Template Limits.Edit'],
            ],
            [
                'name' => 'GetCommissionTemplateLimitFilter',
                'referer' => 'banking/commission-templates/template-settings',
                'binds' => ['Commission Template Limits.Read', 'Commission Template Limits.Edit'],
            ],
            [
                'name' => 'UpdateCommissionTemplateLimit',
                'referer' => 'banking/commission-templates/template-settings',
                'binds' => ['Commission Template Limits.Edit'],
            ],
            [
                'name' => 'DeleteCommissionTemplateLimit',
                'referer' => 'banking/commission-templates/template-settings',
                'binds' => ['Commission Template Limits.Delete'],
                'parents' => ['Commission Template Limits.Read', 'Commission Template Limits.Edit'],
            ],
            [
                'name' => 'ChangeMemberPassword',
                'referer' => null,
            ],
            [
                'name' => 'GetMemberTfaStatus',
                'referer' => null,
            ],
            [
                'name' => 'GetMember2FaData',
                'referer' => null,
            ],
            [
                'name' => 'getMember',
                'referer' => null,
            ],
            [
                'name' => 'GetMemberByField',
                'referer' => null,
            ],
            [
                'name' => 'getMemberData',
                'referer' => null,
            ],
            [
                'name' => 'GetFilterFieldsData',
                'referer' => null,
            ],
            [
                'name' => 'GetFilterFieldsTableData',
                'referer' => null,
            ],
            [
                'name' => 'GetManagerRole',
                'referer' => 'settings/manager-roles/edit',
                'binds' => ['Roles settings.Read', 'Roles settings.Edit'],
            ],
            [
                'name' => 'GetGroups',
                'referer' => 'settings/manager-groups/settings',
                'binds' => ['GroupType settings.Read', 'GroupType settings.Edit'],
            ],
            [
                'name' => 'GetMemberFullName',
                'referer' => 'administration/members/full-profile/profile/security',
                'binds' => ['Member: Security.Edit', 'Member: Security.Read'],
            ],
            [
                'name' => 'GetMemberCoverData',
                'referer' => 'administration/members/full-profile/profile/security',
                'binds' => ['Member: Security.Edit', 'Member: Security.Read'],
            ],
            [
                'name' => 'GetMemberInfoForm',
                'referer' => 'administration/members/full-profile/profile/security',
                'binds' => ['Member: Security.Edit', 'Member: Security.Read'],
            ],
            [
                'name' => 'GetFilterFieldsProfileData',
                'referer' => 'administration/members/full-profile/profile/security',
                'binds' => ['Member: Security.Edit', 'Member: Security.Read'],
            ],
            [
                'name' => 'UpdateMemberQuery',
                'referer' => 'administration/members/full-profile/profile/security',
                'binds' => ['Member: Security.Security Settings'],
                'parents' => ['Member: Security.Edit'],
            ],
        ];

        $lists = PermissionsList::where('type', 'member')->get()->pluck('id')->toArray();
        foreach ($operations as $data) {
            $operation = PermissionOperation::firstOrCreate(['name' => $data['name'], 'referer' => $data['referer']]);

            foreach ($data['binds'] ?? [] as $perName) {
                /** @var Permissions $permission */
                $permission = Permissions::query()->where('name', $perName)->whereIn('permission_list_id', $lists)->first();
                if ($permission) {
                    $ids = $operation->binds()->get()->pluck('id')->push($permission->id)->unique()->toArray();
                    $operation->binds()->sync($ids, true);
                } else {
                    throw new Exception("Not found bind permission in {$data['name']} operation");
                }
            }

            foreach ($data['parents'] ?? [] as $perName) {
                /** @var Permissions $permission */
                $permission = Permissions::query()->where('name', $perName)->whereIn('permission_list_id', $lists)->first();
                if ($permission) {
                    $ids = $operation->parents()->get()->pluck('id')->push($permission->id)->unique()->toArray();
                    $operation->parents()->sync($ids, true);
                } else {
                    throw new Exception("Not found parent permission in {$data['name']} operation");
                }
            }
        }

        $filters = [
            [
                'mode' => PermissionFilter::SCOPE_MODE,
                'action' => null,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'banking',
                'binds' => ['Email Templates:Tag.Banking'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_CREATING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'banking',
                'binds' => ['Email Templates:Tag.Banking'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_UPDATING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'banking',
                'binds' => ['Email Templates:Tag.Banking'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_DELETING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'banking',
                'binds' => ['Email Templates:Tag.Banking'],
            ],
            [
                'mode' => PermissionFilter::SCOPE_MODE,
                'action' => null,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'common',
                'binds' => ['Email Templates:Tag.Common'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_CREATING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'common',
                'binds' => ['Email Templates:Tag.Common'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_UPDATING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'common',
                'binds' => ['Email Templates:Tag.Common'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_DELETING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'common',
                'binds' => ['Email Templates:Tag.Common'],
            ],
            [
                'mode' => PermissionFilter::SCOPE_MODE,
                'action' => null,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'admin notify',
                'binds' => ['Email Templates:Tag.Admin Notify'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_CREATING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'admin notify',
                'binds' => ['Email Templates:Tag.Admin Notify'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_UPDATING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'admin notify',
                'binds' => ['Email Templates:Tag.Admin Notify'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_DELETING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'admin notify',
                'binds' => ['Email Templates:Tag.Admin Notify'],
            ],
            [
                'mode' => PermissionFilter::SCOPE_MODE,
                'action' => null,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'system',
                'binds' => ['Email Templates:Tag.System'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_CREATING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'system',
                'binds' => ['Email Templates:Tag.System'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_UPDATING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'system',
                'binds' => ['Email Templates:Tag.System'],
            ],
            [
                'mode' => PermissionFilter::EVENT_MODE,
                'action' => PermissionFilter::EVENT_DELETING,
                'table' => 'email_templates',
                'column' => 'service_type',
                'value' => 'system',
                'binds' => ['Email Templates:Tag.System'],
            ],

        ];

        foreach ($filters as $filter) {
            $binds = $filter['binds'];
            unset($filter['binds']);
            $permissionFilter = PermissionFilter::firstOrCreate($filter);

            foreach ($binds ?? [] as $perName) {
                /** @var Permissions $permission */
                $permission = Permissions::query()->where('name', $perName)->whereIn('permission_list_id', $lists)->first();
                if ($permission) {
                    $ids = $permissionFilter->binds()->get()->pluck('id')->push($permission->id)->unique()->toArray();
                    $operation->binds()->sync($ids, true);
                } else {
                    throw new Exception("Not found bind permission in {$data['name']} filter");
                }
            }
        }
    }
}
