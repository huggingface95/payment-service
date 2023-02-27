<?php

namespace Database\Seeders;

use App\Enums\ModuleTagEnum;
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
            'KYC Management' => [
                'data' => [
                    'name' => 'KYC Management',
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
                                    'Applicants Individual list.Enabled' => [
                                        'data' => [
                                            'name' => 'Applicants Individual list.Enabled',
                                            'display_name' => 'Read',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'ApplicantIndividualsList',
                                                'referer' => 'management/applicants/individual/list',
                                            ],
                                        ],
                                    ],
                                    'Applicants Individual.Add New Individual' => [
                                        'data' => [
                                            'name' => 'Applicants Individual.Add New Individual',
                                            'display_name' => 'Add New Individual',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'add',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'CreateApplicantIndividual',
                                                'referer' => 'management/applicants/individual/list',
                                                'parents' => ['Applicants Individual list.Enabled']
                                            ],
                                        ],
                                    ],
                                    'Applicants Individual list.Export' => [
                                        'data' => [
                                            'name' => 'Applicants Individual list.Export',
                                            'display_name' => 'Export',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'export',
                                        ],
                                    ],
                                    'Applicants Individual.Delete Applicant' => [
                                        'data' => [
                                            'name' => 'Applicants Individual.Delete Applicant',
                                            'display_name' => 'Delete Applicant',
                                            'guard_name' => 'api',
                                            'order' => 4,
                                            'type' => 'important',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'DeleteApplicantIndividual',
                                                'referer' => 'management/applicants/individual/list',
                                                'parents' => ['Applicants Individual list.Enabled']
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Full Profile:General' => [
                                'data' => [
                                    'name' => 'Individual Full Profile:General',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 2,
                                ],
                                'list' => [
                                    'Individual Full Profile:General.Enabled' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:General.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'ApplicantIndividualItem',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantLinkedCompanies',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantIndividualPersonalInfo',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantIndividualBasicInfo',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantIndividualProfileData',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantIndividualAddress',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:General.Edit' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:General.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'edit',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateApplicantIndividual',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                'parents' => ['Individual Full Profile:General.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:General.Change Account Manager' => [
                                        'data' => [
                                            'name' => 'Individual Profile:General.Change Account Manager',
                                            'display_name' => 'Change Account Manager',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateApplicantIndividualManager',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                'parents' => ['Individual Full Profile:General.Enabled', 'Individual Full Profile:General.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:General.Edit Labels' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:General.Edit Labels',
                                            'display_name' => 'Edit Labels',
                                            'guard_name' => 'api',
                                            'order' => 4,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateApplicantIndividualLabel',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                'parents' => ['Individual Full Profile:General.Enabled', 'Individual Full Profile:General.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:General.Linked Corporate' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:General.Linked Corporate',
                                            'display_name' => 'Linked Corporate',
                                            'guard_name' => 'api',
                                            'order' => 5,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'ApplicantLinkedCompanies',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                'parents' => ['Individual Full Profile:General.Enabled', 'Individual Full Profile:General.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:General.Add New Corporate' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:General.Add New Corporate',
                                            'display_name' => 'Add New Corporate',
                                            'guard_name' => 'api',
                                            'order' => 6,
                                            'type' => 'add',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'CreateApplicantIndividualCompany',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                'parents' => ['Individual Full Profile:General.Enabled'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Full Profile:Modules' => [
                                'data' => [
                                    'name' => 'Individual Full Profile:Modules',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 3,
                                ],
                                'list' => [
                                    'Individual Full Profile:Modules.Enabled' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Modules.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantModules',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules',
                                            ],
                                        ],

                                    ],
                                    'Individual Full Profile:Modules.Edit' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Modules.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Individual Full Profile:Modules.Add Module' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Modules.Add Module',
                                            'display_name' => 'Add Module',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'add',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'CreateIndividualModule',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules',
                                                'parents' => ['Individual Full Profile:Modules.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:Modules.Module Status' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Modules.Module Status',
                                            'display_name' => 'Module Status',
                                            'guard_name' => 'api',
                                            'order' => 4,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateIndividualModule',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:Modules.Delete Module' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Modules.Delete Module',
                                            'display_name' => 'Delete Module',
                                            'guard_name' => 'api',
                                            'order' => 5,
                                            'type' => 'important',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'DeleteIndividualModule',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Individual Full Profile:Modules.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:Modules.Account Settings and Login' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Modules.Account Settings and Login',
                                            'display_name' => 'Account Settings and Login',
                                            'guard_name' => 'api',
                                            'order' => 6,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateIndividual2FAStatus',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                            ],
                                            [
                                                'name' => 'UpdateIndividualPassword',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                            ],
                                            [
                                                'name' => 'SendEmailRegistration',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                            ],
                                            [
                                                'name' => 'SendEmailResetPassword',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:Modules.Group Settings' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Modules.Group Settings',
                                            'display_name' => 'Group Settings',
                                            'guard_name' => 'api',
                                            'order' => 7,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetIndividualGroupInfo',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                            ],
                                            [
                                                'name' => 'UpdateIndividualGroupSettings',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:Modules.Grand Access Block' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Modules.Grand Access Block',
                                            'display_name' => 'Grand Access Block',
                                            'guard_name' => 'api',
                                            'order' => 8,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetIndividualGrantAccess',
                                                'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Full Profile:Active Session' => [
                                'data' => [
                                    'name' => 'Individual Full Profile:Active Session',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 4,
                                ],
                                'list' => [
                                    'Individual Full Profile:Active Session.Enabled' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Active Session.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Full Profile:Authentication Log' => [
                                'data' => [
                                    'name' => 'Individual Full Profile:Authentication Log',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 5,
                                ],
                                'list' => [
                                    'Individual Full Profile:Authentication Log.Enabled' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Authentication Log.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Full Profile:KYC Timeline' => [
                                'data' => [
                                    'name' => 'Individual Full Profile:KYC Timeline',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 6,
                                ],
                                'list' => [
                                    'Individual Full Profile:KYC Timeline.Enabled' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:KYC Timeline.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetKycTimelines',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                            [
                                                'name' => 'GetApplicantKycLatestDocuments',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                            [
                                                'name' => 'GetApplicantIndividualRiskLevelHistory',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                            [
                                                'name' => 'ApplicantRiskLevelFilter',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                            [
                                                'name' => 'GetApplicantIndividualNotes',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:KYC Timeline.Edit' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:KYC Timeline.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'edit',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'AddApplicantIndividualRiskLevelHistory',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                                'parents' => ['Individual Full Profile:Modules.Enabled'],
                                            ],
                                            [
                                                'name' => 'AddApplicantIndividualNote',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                                'parents' => ['Individual Full Profile:Modules.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:KYC Timeline.Account Status' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:KYC Timeline.Account Status',
                                            'display_name' => 'Account Status',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Full Profile:KYC Timeline.Verification Status' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:KYC Timeline.Verification Status',
                                            'display_name' => 'Verification Status',
                                            'guard_name' => 'api',
                                            'order' => 4,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Individual Full Profile:KYC Timeline.Risk Level' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:KYC Timeline.Risk Level',
                                            'display_name' => 'Risk Level',
                                            'guard_name' => 'api',
                                            'order' => 5,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantIndividualRiskLevelHistory',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:KYC Timeline.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:KYC Timeline.Internal Notes' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:KYC Timeline.Internal Notes',
                                            'display_name' => 'Internal Notes',
                                            'guard_name' => 'api',
                                            'order' => 6,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantIndividualNotes',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                                'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:KYC Timeline.Edit'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'Individual Full Profile:Documents' => [
                                'data' => [
                                    'name' => 'Individual Full Profile:Documents',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 7,
                                ],
                                'list' => [
                                    'Individual Full Profile:Documents.Enabled' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Documents.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantKycLatestDocumentsData',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/documents',
                                            ],
                                            [
                                                'name' => 'GetApplicantKycDocuments',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/documents',
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:Documents.Edit' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Documents.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'edit',
                                        ],
                                    ],
                                    'Individual Full Profile:Documents.Add New Document' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Documents.Add New Document',
                                            'display_name' => 'Add New Document',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'add',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'AddApplicantDocument',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/documents',
                                                'parents' => ['Individual Full Profile:Documents.Enabled', 'Individual Full Profile:Documents.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:Documents.Reject Details' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Documents.Reject Details',
                                            'display_name' => 'Reject Details',
                                            'guard_name' => 'api',
                                            'order' => 4,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'DeleteApplicantDocument',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/documents',
                                                'parents' => ['Individual Full Profile:Documents.Enabled', 'Individual Full Profile:Documents.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:Documents.Add New Category' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Documents.Add New Category',
                                            'display_name' => 'Add New Category',
                                            'guard_name' => 'api',
                                            'order' => 5,
                                            'type' => 'add',
                                        ],
                                    ],
                                    'Individual Full Profile:Documents.Add New Tag' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Documents.Add New Tag',
                                            'display_name' => 'Add New Tag',
                                            'guard_name' => 'api',
                                            'order' => 6,
                                            'type' => 'add',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'CreateApplicantDocumentTag',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/documents',
                                                'parents' => ['Individual Full Profile:Documents.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Individual Full Profile:Documents.Internal Notes' => [
                                        'data' => [
                                            'name' => 'Individual Full Profile:Documents.Internal Notes',
                                            'display_name' => 'Internal Notes',
                                            'guard_name' => 'api',
                                            'order' => 7,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantKycLatestDocumentsData',
                                                'referer' => 'management/applicants/individual/full-profile/$id/kyc/documents',
                                                'parents' => ['Individual Full Profile:Documents.Enabled', 'Individual Full Profile:Documents.Edit'],
                                            ],
                                        ],
                                    ],

                                ],
                            ],
                            'Applicants:Corporate List' => [
                                'data' => [
                                    'name' => 'Applicants:Corporate List',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 8,
                                ],
                                'list' => [
                                    'Applicants:Corporate List.Enabled' => [
                                        'data' => [
                                            'name' => 'Applicants:Corporate List.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'ApplicantCompaniesList',
                                                'referer' => 'management/applicants/corporate/list',
                                            ],
                                        ],
                                    ],
                                    'Applicants:Corporate List.Add New Document' => [
                                        'data' => [
                                            'name' => 'Applicants:Corporate List.Add New Document',
                                            'display_name' => 'Add New Document',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'add',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'CreateApplicantCorporate',
                                                'referer' => 'management/applicants/corporate/list',
                                                'parents' => ['Applicants:Corporate List.Enabled']
                                            ],
                                        ],
                                    ],
                                    'Applicants:Corporate List.Export' => [
                                        'data' => [
                                            'name' => 'Applicants:Corporate List.Export',
                                            'display_name' => 'Export',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'export',
                                        ],
                                    ],
                                    'Applicants:Corporate List.Delete Applicant' => [
                                        'data' => [
                                            'name' => 'Applicants:Corporate List.Delete Applicant',
                                            'display_name' => 'Delete Applicant',
                                            'guard_name' => 'api',
                                            'order' => 4,
                                            'type' => 'important',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'DeleteApplicantCompany',
                                                'referer' => 'management/applicants/corporate/list',
                                                'parents' => ['Applicants:Corporate List.Enabled']
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'Corporate Full Profile:General' => [
                                'data' => [
                                    'name' => 'Corporate Full Profile:General',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 9,
                                ],
                                'list' => [
                                    'Corporate Full Profile:General.Enabled' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:General.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'ApplicantCorporateItem',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantCompanyBasicInfo',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantCompanyProfileData',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantCompanyCorporateInfo',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantCompanyContacts',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                            ],
                                            [
                                                'name' => 'ApplicantBoardMembers',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:General.Edit' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:General.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'edit',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateApplicantCompany',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                'parents' => ['Corporate Full Profile:General.Enabled']
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:General.Change Account Manager' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:General.Change Account Manager',
                                            'display_name' => 'Change Account Manager',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateApplicantCompany',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                'parents' => ['Corporate Full Profile:General.Enabled', 'Corporate Full Profile:General.Edit']
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:General.Edit Labels' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:General.Edit Labels',
                                            'display_name' => 'Edit Labels',
                                            'guard_name' => 'api',
                                            'order' => 4,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateApplicantCompanyLabel',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                'parents' => ['Corporate Full Profile:General.Enabled', 'Corporate Full Profile:General.Edit']
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:General.Linked Board Members' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:General.Linked Board Members',
                                            'display_name' => 'Linked Board Members',
                                            'guard_name' => 'api',
                                            'order' => 5,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'ApplicantBoardMembers',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                'parents' => ['Corporate Full Profile:General.Enabled', 'Corporate Full Profile:General.Edit']
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:General.Add New Board Member' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:General.Add New Board Member',
                                            'display_name' => 'Add New Board Member',
                                            'guard_name' => 'api',
                                            'order' => 6,
                                            'type' => 'add',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'CreateApplicantIndividualCompany',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                'parents' => ['Corporate Full Profile:General.Enabled']
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'Corporate:Modules' => [
                                'data' => [
                                    'name' => 'Corporate:Modules',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 10,
                                ],
                                'list' => [
                                    'Corporate:Modules.Enabled' => [
                                        'data' => [
                                            'name' => 'Corporate:Modules.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantModules',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                            ],
                                            [
                                                'name' => 'GetCorporateModules',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                            ],
                                            [
                                                'name' => 'GetCorporateGroupInfo',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                            ],
                                            [
                                                'name' => 'GetMatchedIndividuals',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                            ],
                                            [
                                                'name' => 'GetCorporateBankingAccessList',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                            ],
                                        ],
                                    ],
                                    'Corporate:Modules.Edit' => [
                                        'data' => [
                                            'name' => 'Corporate:Modules.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'edit',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'DeleteCorporateModule',
                                                'referer' => 'management/applicants/corporate/full-profile/$id',
                                                'parents' => ['Corporate:Modules.Enabled'],
                                            ],
                                            [
                                                'name' => 'CreateApplicantBankingAccess',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled'],
                                            ],
                                            [
                                                'name' => 'DeleteBankingAccess',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled'],
                                            ],
                                            [
                                                'name' => 'UpdateApplicantBankingAccess',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Corporate:Modules.Add Banking Module' => [
                                        'data' => [
                                            'name' => 'Corporate:Modules.Add Banking Module',
                                            'display_name' => 'Add Banking Module',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'add',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'CreateCorporateModule',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Corporate:Modules.Module Status' => [
                                        'data' => [
                                            'name' => 'Corporate:Modules.Module Status',
                                            'display_name' => 'Module Status',
                                            'guard_name' => 'api',
                                            'order' => 4,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateCorporateModule',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled', 'Corporate:Modules.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Corporate:Modules.Delete Module' => [
                                        'data' => [
                                            'name' => 'Corporate:Modules.Delete Module',
                                            'display_name' => 'Delete Module',
                                            'guard_name' => 'api',
                                            'order' => 5,
                                            'type' => 'important',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'DeleteCorporateModule',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Corporate:Modules.Group Settings' => [
                                        'data' => [
                                            'name' => 'Corporate:Modules.Group Settings',
                                            'display_name' => 'Group Settings',
                                            'guard_name' => 'api',
                                            'order' => 6,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetCorporateGroupInfo',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled', 'Corporate:Modules.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Corporate:Modules.Grand Access' => [
                                        'data' => [
                                            'name' => 'Corporate:Modules.Grand Access',
                                            'display_name' => 'Grand Access',
                                            'guard_name' => 'api',
                                            'order' => 7,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'CreateApplicantBankingAccess',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled', 'Corporate:Modules.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Corporate:Modules.Access to Online Banking' => [
                                        'data' => [
                                            'name' => 'Corporate:Modules.Access to Online Banking',
                                            'display_name' => 'Access to Online Banking',
                                            'guard_name' => 'api',
                                            'order' => 8,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetCorporateBankingAccessList',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled', 'Corporate:Modules.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Corporate:Modules.Delete Module Access' => [
                                        'data' => [
                                            'name' => 'Corporate:Modules.Delete Module Access',
                                            'display_name' => 'Delete Module Access',
                                            'guard_name' => 'api',
                                            'order' => 9,
                                            'type' => 'important',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'DeleteBankingAccess',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                'parents' => ['Corporate:Modules.Enabled'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'Corporate Full Profile:Active Session' => [
                                'data' => [
                                    'name' => 'Corporate Full Profile:Active Session',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 11,
                                ],
                                'list' => [
                                    'Corporate Full Profile:Active Session.Enabled' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:Active Session.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Corporate Full Profile:Authentication Log' => [
                                'data' => [
                                    'name' => 'Corporate Full Profile:Authentication Log',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 12,
                                ],
                                'list' => [
                                    'Corporate Full Profile:Authentication Log.Enabled' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:Authentication Log.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                    ],
                                ],
                            ],
                            'Corporate Full Profile:KYC Timeline' => [
                                'data' => [
                                    'name' => 'Corporate Full Profile:KYC Timeline',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 13,
                                ],
                                'list' => [
                                    'Corporate Full Profile:KYC Timeline.Enabled' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:KYC Timeline.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetKycTimelines',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                            [
                                                'name' => 'GetApplicantCompanyContactVerification',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                            [
                                                'name' => 'GetApplicantKycLatestDocuments',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                            [
                                                'name' => 'GetApplicantCompanyRiskLevelHistory',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                            [
                                                'name' => 'ApplicantRiskLevelFilter',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                            [
                                                'name' => 'GetApplicantCompanyNotes',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:KYC Timeline.Edit' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:KYC Timeline.Edit',
                                            'display_name' => 'Edit',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'edit',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'AddApplicantCompanyRiskLevelHistory',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                'parents' => ['Corporate Full Profile:KYC Timeline.Enabled'],
                                            ],
                                            [
                                                'name' => 'AddApplicantCompanyNote',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                'parents' => ['Corporate Full Profile:KYC Timeline.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:KYC Timeline.Account Status' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:KYC Timeline.Account Status',
                                            'display_name' => 'Account Status',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'info',
                                        ],
                                    ],
                                    'Corporate Full Profile:KYC Timeline.Verification Status' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:KYC Timeline.Verification Status',
                                            'display_name' => 'Verification Status',
                                            'guard_name' => 'api',
                                            'order' => 4,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantCompanyContactVerification',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                'parents' => ['Corporate Full Profile:KYC Timeline.Enabled', 'Corporate Full Profile:KYC Timeline.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:KYC Timeline.Risk Level' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:KYC Timeline.Risk Level',
                                            'display_name' => 'Risk Level',
                                            'guard_name' => 'api',
                                            'order' => 5,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantCompanyRiskLevelHistory',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                'parents' => ['Corporate Full Profile:KYC Timeline.Enabled', 'Corporate Full Profile:KYC Timeline.Edit'],
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:KYC Timeline.Internal Notes' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:KYC Timeline.Internal Notes',
                                            'display_name' => 'Internal Notes',
                                            'guard_name' => 'api',
                                            'order' => 6,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantCompanyNotes',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                'parents' => ['Corporate Full Profile:KYC Timeline.Enabled', 'Corporate Full Profile:KYC Timeline.Edit'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'Corporate Full Profile:Documents' => [
                                'data' => [
                                    'name' => 'Corporate Full Profile:Documents',
                                    'type' => 'member',
                                    'separator' => null,
                                    'order' => 14,
                                ],
                                'list' => [
                                    'Corporate Full Profile:Documents.Enabled' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:Documents.Enabled',
                                            'display_name' => 'Enabled',
                                            'guard_name' => 'api',
                                            'order' => 1,
                                            'type' => 'read',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'GetApplicantKycDocuments',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/documents',
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:Documents.Add New Document' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:Documents.Add New Document',
                                            'display_name' => 'Add New Document',
                                            'guard_name' => 'api',
                                            'order' => 2,
                                            'type' => 'add',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'AddApplicantDocument',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/documents',
                                                'parents' => ['Corporate Full Profile:Documents.Enabled'],
                                            ],
                                        ],
                                    ],
                                    'Corporate Full Profile:Documents.Change Status' => [
                                        'data' => [
                                            'name' => 'Corporate Full Profile:Documents.Change Status',
                                            'display_name' => 'Change Status',
                                            'guard_name' => 'api',
                                            'order' => 3,
                                            'type' => 'info',
                                        ],
                                        'operations' => [
                                            [
                                                'name' => 'UpdateApplicantDocument',
                                                'referer' => 'management/applicants/corporate/full-profile/$id/kyc/documents',
                                                'parents' => ['Corporate Full Profile:Documents.Enabled', 'Corporate Full Profile:Documents.Add New Document'],
                                            ],
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
                            unset($permission['data']['order']);

                            unset($permission['data']['order']);
                            /** @var Permissions $p */
                            $p = Permissions::firstOrCreate($permission['data']);
                            $p->order = $order;

                            $p->save();


                            foreach ($permission['operations'] ?? [] as $o) {
                                $operation = PermissionOperation::firstOrCreate(['name' => $o['name'], 'referer' => $o['referer']]);
                                $ids = $operation->binds()->get()->pluck('id')->push($p->id)->unique()->toArray();
                                $operation->binds()->sync($ids, true);

                                foreach ($o['parents'] ?? [] as $perName) {
                                    /** @var Permissions $permission */
                                    //TODO  $lists = PermissionsList::where('type', 'member')->get()->pluck('id')->toArray();
                                    $p = Permissions::query()->where('name', $perName)->first();
                                    if ($permission) {
                                        $ids = $operation->parents()->get()->pluck('id')->push($p->id)->unique()->toArray();
                                        $operation->parents()->sync($ids, true);
                                    } else {
                                        throw new Exception("Not found bind permission in {$o['name']} operation");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $operations = [
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
        ];

        foreach ($operations as $data) {
            PermissionOperation::firstOrCreate(['name' => $data['name'], 'referer' => $data['referer']]);
        }


//        $filters = [
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'banking',
//                'binds' => ['Email Templates:Tag.Administration: Banking'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'banking',
//                'binds' => ['Email Templates:Tag.Administration: Banking'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'banking',
//                'binds' => ['Email Templates:Tag.Administration: Banking'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'banking',
//                'binds' => ['Email Templates:Tag.Administration: Banking'],
//            ],
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'common',
//                'binds' => ['Email Templates:Tag.Administration: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'common',
//                'binds' => ['Email Templates:Tag.Administration: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'common',
//                'binds' => ['Email Templates:Tag.Administration: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'common',
//                'binds' => ['Email Templates:Tag.Administration: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.Administration: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.Administration: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.Administration: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.Administration: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'system',
//                'binds' => ['Email Templates:Tag.Administration: System'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'system',
//                'binds' => ['Email Templates:Tag.Administration: System'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'system',
//                'binds' => ['Email Templates:Tag.Administration: System'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'system',
//                'binds' => ['Email Templates:Tag.Administration: System'],
//            ],
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'common',
//                'binds' => ['Email Templates:Tag.KYC: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'common',
//                'binds' => ['Email Templates:Tag.KYC: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'common',
//                'binds' => ['Email Templates:Tag.KYC: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'common',
//                'binds' => ['Email Templates:Tag.KYC: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.KYC: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.KYC: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.KYC: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.KYC: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'system',
//                'binds' => ['Email Templates:Tag.KYC: System'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'system',
//                'binds' => ['Email Templates:Tag.KYC: System'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'system',
//                'binds' => ['Email Templates:Tag.KYC: System'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'system',
//                'binds' => ['Email Templates:Tag.KYC: System'],
//            ],
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => ModuleTagEnum::BANKING_COMMON->value,
//                'binds' => ['Email Templates:Tag.Banking: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => ModuleTagEnum::BANKING_COMMON->value,
//                'binds' => ['Email Templates:Tag.Banking: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => ModuleTagEnum::BANKING_COMMON->value,
//                'binds' => ['Email Templates:Tag.Banking: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => ModuleTagEnum::BANKING_COMMON->value,
//                'binds' => ['Email Templates:Tag.Banking: Common'],
//            ],
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.Banking: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.Banking: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.Banking: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => 'admin notify',
//                'binds' => ['Email Templates:Tag.Banking: Admin Notify'],
//            ],
//            [
//                'mode' => PermissionFilter::SCOPE_MODE,
//                'action' => null,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => ModuleTagEnum::BANKING_SYSTEM->value,
//                'binds' => ['Email Templates:Tag.Banking: System'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_CREATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => ModuleTagEnum::BANKING_SYSTEM->value,
//                'binds' => ['Email Templates:Tag.Banking: System'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_UPDATING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => ModuleTagEnum::BANKING_SYSTEM->value,
//                'binds' => ['Email Templates:Tag.Banking: System'],
//            ],
//            [
//                'mode' => PermissionFilter::EVENT_MODE,
//                'action' => PermissionFilter::EVENT_DELETING,
//                'table' => 'email_templates',
//                'column' => 'service_type',
//                'value' => ModuleTagEnum::BANKING_SYSTEM->value,
//                'binds' => ['Email Templates:Tag.Banking: System'],
//            ],
//        ];
//        $lists = PermissionsList::where('type', 'member')->get()->pluck('id')->toArray();
//        foreach ($filters as $filter) {
//            $binds = $filter['binds'];
//            unset($filter['binds']);
//            $permissionFilter = PermissionFilter::firstOrCreate($filter);
//
//            foreach ($binds ?? [] as $perName) {
//                /** @var Permissions $permission */
//                $permission = Permissions::query()->where('name', $perName)->whereIn('permission_list_id', $lists)->first();
//                if ($permission) {
//                    $ids = $permissionFilter->binds()->get()->pluck('id')->push($permission->id)->unique()->toArray();
//                    $operation->binds()->sync($ids, true);
//                } else {
//                    throw new Exception("Not found bind permission in {$data['name']} filter");
//                }
//            }
//        }
    }
}
