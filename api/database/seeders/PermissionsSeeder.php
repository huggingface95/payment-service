<?php

namespace Database\Seeders;

use App\Enums\EmailTemplatesTypeEnum;
use App\Enums\FeeTypeEnum;
use App\Enums\GroupTypeEnum;
use App\Enums\ModuleTagEnum;
use App\Models\EmailNotification;
use App\Models\PermissionCategory;
use App\Models\PermissionFilter;
use App\Models\PermissionOperation;
use App\Models\Permissions;
use App\Models\PermissionsList;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
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
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'ApplicantIndividualsList',
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividuals',
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
                                                    'parents' => ['Applicants Individual list.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantIndividual',
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
                                                    'parents' => ['Applicants Individual list.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'deleteApplicantIndividual',
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
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividual',

                                                ],
                                                [
                                                    'name' => 'ApplicantLinkedCompanies',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'applicantLinkedCompanies',

                                                ],
                                                [
                                                    'name' => 'ApplicantIndividualPersonalInfo',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividual',

                                                ],
                                                [
                                                    'name' => 'ApplicantIndividualBasicInfo',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividual',

                                                ],
                                                [
                                                    'name' => 'ApplicantIndividualProfileData',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividual',

                                                ],
                                                [
                                                    'name' => 'ApplicantIndividualAddress',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividual',

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
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantIndividual',

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
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantIndividual',

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
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantIndividualLabel',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantLinkedCompanies',

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
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantIndividualCompany',

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
                                                    'type' => 'query',
                                                    'method' => 'modules',

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
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantIndividualModule',

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
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantIndividualModule',

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
                                                    'type' => 'mutation',
                                                    'method' => 'deleteApplicantIndividualModule',

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
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantIndividual',

                                                ],
                                                [
                                                    'name' => 'UpdateIndividualPassword',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                    'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'setApplicantIndividualPassword',

                                                ],
                                                [
                                                    'name' => 'SendEmailRegistration',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                    'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'sendEmailRegistation',

                                                ],
                                                [
                                                    'name' => 'SendEmailResetPassword',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                    'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'sendEmailResetPassword',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividual',

                                                ],
                                                [
                                                    'name' => 'UpdateIndividualGroupSettings',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/profile/modules/banking-module',
                                                    'parents' => ['Individual Full Profile:Modules.Enabled', 'Individual Full Profile:Modules.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantIndividual',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividual',

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
                                                    'type' => 'query',
                                                    'method' => 'kycTimelines',

                                                ],
                                                [
                                                    'name' => 'GetApplicantKycLatestDocuments',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                                    'type' => 'query',
                                                    'method' => 'applicantDocuments',

                                                ],
                                                [
                                                    'name' => 'GetApplicantIndividualRiskLevelHistory',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                                    'type' => 'query',
                                                    'method' => 'applicantRiskLevelHistory',

                                                ],
                                                [
                                                    'name' => 'ApplicantRiskLevelFilter',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                                    'type' => 'query',
                                                    'method' => 'applicantRiskLevels',

                                                ],
                                                [
                                                    'name' => 'GetApplicantIndividualNotes',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividualNotes',

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
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantRiskLevelHistory',

                                                ],
                                                [
                                                    'name' => 'AddApplicantIndividualNote',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/kyc/kyc-timeline',
                                                    'parents' => ['Individual Full Profile:Modules.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantIndividualNote',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantRiskLevels',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantIndividualNotes',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantDocuments',

                                                ],
                                                [
                                                    'name' => 'GetApplicantKycDocuments',
                                                    'referer' => 'management/applicants/individual/full-profile/$id/kyc/documents',
                                                    'type' => 'query',
                                                    'method' => 'applicantDocuments',

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
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantDocument',

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
                                                    'type' => 'mutation',
                                                    'method' => 'deleteApplicantDocument',

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
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantDocumentTag',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantDocuments',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantCompanies',

                                                ],
                                            ],
                                        ],
                                        'Applicants:Corporate List.Add New Corporate' => [
                                            'data' => [
                                                'name' => 'Applicants:Corporate List.Add New Corporate',
                                                'display_name' => 'Add New Corporate',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateApplicantCorporate',
                                                    'referer' => 'management/applicants/corporate/list',
                                                    'parents' => ['Applicants:Corporate List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantCompany',

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
                                                    'parents' => ['Applicants:Corporate List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'deleteApplicantCompany',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

                                                ],
                                                [
                                                    'name' => 'ApplicantCompanyBasicInfo',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

                                                ],
                                                [
                                                    'name' => 'ApplicantCompanyProfileData',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

                                                ],
                                                [
                                                    'name' => 'ApplicantCompanyCorporateInfo',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

                                                ],
                                                [
                                                    'name' => 'ApplicantCompanyContacts',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

                                                ],
                                                [
                                                    'name' => 'ApplicantBoardMembers',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'getMatchedUsers',

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
                                                    'parents' => ['Corporate Full Profile:General.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantCompany',

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
                                                    'parents' => ['Corporate Full Profile:General.Enabled', 'Corporate Full Profile:General.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantCompany',

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
                                                    'parents' => ['Corporate Full Profile:General.Enabled', 'Corporate Full Profile:General.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantCompanyLabel',

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
                                                    'parents' => ['Corporate Full Profile:General.Enabled', 'Corporate Full Profile:General.Edit'],
                                                    'type' => 'query',
                                                    'method' => 'getMatchedUsers',

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
                                                    'parents' => ['Corporate Full Profile:General.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantIndividualCompany',

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
                                                    'type' => 'query',
                                                    'method' => 'modules',

                                                ],
                                                [
                                                    'name' => 'GetCorporateModules',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

                                                ],
                                                [
                                                    'name' => 'GetCorporateGroupInfo',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

                                                ],
                                                [
                                                    'name' => 'GetMatchedIndividuals',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                    'type' => 'query',
                                                    'method' => 'getMatchedUsers',

                                                ],
                                                [
                                                    'name' => 'GetCorporateBankingAccessList',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                    'type' => 'query',
                                                    'method' => 'applicantBankingAccesses',

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
                                                    'type' => 'mutation',
                                                    'method' => 'deleteApplicantCompanyModule',

                                                ],
                                                [
                                                    'name' => 'CreateApplicantBankingAccess',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                    'parents' => ['Corporate:Modules.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantBankingAccess',

                                                ],
                                                [
                                                    'name' => 'DeleteBankingAccess',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                    'parents' => ['Corporate:Modules.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'deleteApplicantBankingAccess',

                                                ],
                                                [
                                                    'name' => 'UpdateApplicantBankingAccess',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/profile/modules/banking-module',
                                                    'parents' => ['Corporate:Modules.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantBankingAccess',

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
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantCompanyModule',

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
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantCompanyModule',

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
                                                    'type' => 'mutation',
                                                    'method' => 'deleteApplicantCompanyModule',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

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
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantBankingAccess',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantBankingAccesses',

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
                                                    'type' => 'mutation',
                                                    'method' => 'deleteApplicantBankingAccess',

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
                                                    'type' => 'query',
                                                    'method' => 'kycTimelines',

                                                ],
                                                [
                                                    'name' => 'GetApplicantCompanyContactVerification',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

                                                ],
                                                [
                                                    'name' => 'GetApplicantKycLatestDocuments',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                    'type' => 'query',
                                                    'method' => 'applicantDocuments',

                                                ],
                                                [
                                                    'name' => 'GetApplicantCompanyRiskLevelHistory',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                    'type' => 'query',
                                                    'method' => 'applicantCompanyRiskLevelHistory',

                                                ],
                                                [
                                                    'name' => 'ApplicantRiskLevelFilter',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                    'type' => 'query',
                                                    'method' => 'applicantRiskLevels',

                                                ],
                                                [
                                                    'name' => 'GetApplicantCompanyNotes',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                    'type' => 'query',
                                                    'method' => 'applicantCompanyNotes',

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
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantCompanyRiskLevelHistory',

                                                ],
                                                [
                                                    'name' => 'AddApplicantCompanyNote',
                                                    'referer' => 'management/applicants/corporate/full-profile/$id/kyc/kyc-timeline',
                                                    'parents' => ['Corporate Full Profile:KYC Timeline.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantCompanyNote',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantCompany',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantCompanyRiskLevelHistory',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantCompanyNotes',

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
                                                    'type' => 'query',
                                                    'method' => 'applicantDocuments',

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
                                                    'type' => 'mutation',
                                                    'method' => 'createApplicantDocument',

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
                                                    'type' => 'mutation',
                                                    'method' => 'updateApplicantDocument',

                                                ],
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
                                'Role List' => [
                                    'data' => [
                                        'name' => 'Role List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 1,
                                    ],
                                    'list' => [
                                        'Role List.Enabled' => [
                                            'data' => [
                                                'name' => 'Role List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetRoleList',
                                                    'referer' => 'settings/manager-roles/list',
                                                    'type' => 'query',
                                                    'method' => 'roles',

                                                ],
                                            ],
                                        ],
                                        'Role List.Add New Role' => [
                                            'data' => [
                                                'name' => 'Role List.Add New Role',
                                                'display_name' => 'Add New Role',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateRole',
                                                    'referer' => 'settings/manager-roles/new',
                                                    'parents' => ['Role List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createRole',

                                                ],
                                            ],
                                        ],
                                        'Role List.Show User list' => [
                                            'data' => [
                                                'name' => 'Role List.Show User list',
                                                'display_name' => 'Show User list',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetUsers',
                                                    'referer' => 'settings/manager-roles/list',
                                                    'parents' => ['Role List.Enabled'],
                                                    'type' => 'query',
                                                    'method' => 'users',

                                                ],
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
                                        'Roles Settings.Enabled' => [
                                            'data' => [
                                                'name' => 'Roles Settings.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetRole',
                                                    'referer' => 'settings/manager-roles/settings',
                                                    'type' => 'query',
                                                    'method' => 'role',

                                                ],
                                            ],
                                        ],
                                        'Roles Settings.Edit' => [
                                            'data' => [
                                                'name' => 'Roles Settings.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateRole',
                                                    'referer' => 'settings/manager-roles/settings',
                                                    'parents' => ['Roles Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateRole',

                                                ],
                                            ],
                                        ],
                                        'Roles Settings.Delete' => [
                                            'data' => [
                                                'name' => 'Roles Settings.Delete',
                                                'display_name' => 'Delete',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'important',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'DeleteRole',
                                                    'referer' => 'settings/manager-roles/settings',
                                                    'parents' => ['Roles Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'deleteRole',

                                                ],
                                            ],
                                        ],
                                        'Roles Settings.Group Type: Individual' => [
                                            'data' => [
                                                'name' => 'Roles Settings.Group Type: Individual',
                                                'display_name' => 'Group Type: Individual',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Roles Settings.Group Type: Corporate' => [
                                            'data' => [
                                                'name' => 'Roles Settings.Group Type: Corporate',
                                                'display_name' => 'Group Type: Corporate',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Roles Settings.Group Type: Member' => [
                                            'data' => [
                                                'name' => 'Roles Settings.Group Type: Member',
                                                'display_name' => 'Group Type: Member',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Groups List' => [
                                    'data' => [
                                        'name' => 'Groups List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 3,
                                    ],
                                    'list' => [
                                        'Groups List.Enabled' => [
                                            'data' => [
                                                'name' => 'Groups List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetGroupsList',
                                                    'referer' => 'settings/manager-groups/list',
                                                    'type' => 'query',
                                                    'method' => 'groupList',

                                                ],
                                            ],
                                        ],
                                        'Groups List.Add New Group' => [
                                            'data' => [
                                                'name' => 'Groups List.Add New Group',
                                                'display_name' => 'Add New Group',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateGroup',
                                                    'referer' => 'settings/manager-groups/new',
                                                    'parents' => ['Groups List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createGroupSettings',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Groups Settings' => [
                                    'data' => [
                                        'name' => 'Groups Settings',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 4,
                                    ],
                                    'list' => [
                                        'Groups Settings.Enabled' => [
                                            'data' => [
                                                'name' => 'Groups Settings.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetGroup',
                                                    'referer' => 'settings/manager-groups/settings',
                                                    'type' => 'query',
                                                    'method' => 'groups',

                                                ],
                                            ],
                                        ],
                                        'Groups Settings.Edit' => [
                                            'data' => [
                                                'name' => 'Groups Settings.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateGroup',
                                                    'referer' => 'settings/manager-groups/settings',
                                                    'parents' => ['Groups Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateGroupSettings',

                                                ],
                                            ],
                                        ],
                                        'Groups Settings.Delete' => [
                                            'data' => [
                                                'name' => 'Groups Settings.Delete',
                                                'display_name' => 'Delete',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'important',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'DeleteGroupSetting',
                                                    'referer' => 'settings/manager-groups/settings',
                                                    'parents' => ['Groups Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'deleteGroup',

                                                ],
                                            ],
                                        ],
                                        'Groups Settings.Group Type: Individual' => [
                                            'data' => [
                                                'name' => 'Groups Settings.Group Type: Individual',
                                                'display_name' => 'Group Type: Individual',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Groups Settings.Group Type: Corporate' => [
                                            'data' => [
                                                'name' => 'Groups Settings.Group Type: Corporate',
                                                'display_name' => 'Group Type: Corporate',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Groups Settings.Group Type: Member' => [
                                            'data' => [
                                                'name' => 'Groups Settings.Group Type: Member',
                                                'display_name' => 'Group Type: Member',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
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
                                'Member Company List' => [
                                    'data' => [
                                        'name' => 'Member Company List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 1,
                                    ],
                                    'list' => [
                                        'Member Company List.Enabled' => [
                                            'data' => [
                                                'name' => 'Member Company List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'MemberCompanies',
                                                    'referer' => 'administration/member-info/member-company-list',
                                                    'type' => 'mutation',
                                                    'method' => 'companies',

                                                ],
                                            ],
                                        ],
                                        'Member Company List.Add Member Company' => [
                                            'data' => [
                                                'name' => 'Member Company List.Add Member Company',
                                                'display_name' => 'Add Member Company',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateMemberCompany',
                                                    'referer' => 'administration/member-info/member-company-list',
                                                    'parents' => ['Member Company List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createCompany',

                                                ],
                                            ],
                                        ],
                                        'Member Company List.Delete' => [
                                            'data' => [
                                                'name' => 'Member Company List.Delete',
                                                'display_name' => 'Delete',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'important',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'DeleteCompany',
                                                    'referer' => 'administration/member-info/member-company-list',
                                                    'parents' => ['Member Company List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'deleteCompany',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Member Company Profile:General' => [
                                    'data' => [
                                        'name' => 'Member Company Profile:General',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 2,
                                    ],
                                    'list' => [
                                        'Member Company Profile:General.Enabled' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:General.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetMemberCompanyById',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/general',
                                                    'type' => 'mutation',
                                                    'method' => 'company',

                                                ],
                                                [
                                                    'name' => 'GetSettingsCompanyById',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'company',

                                                ],
                                                [
                                                    'name' => 'GetProfileDataCompanyById',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'company',

                                                ],
                                                [
                                                    'name' => 'MemberProfile',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'memberProfile',

                                                ],
                                                [
                                                    'name' => 'GetSettingsCompanyById',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/general',
                                                    'type' => 'query',
                                                    'method' => 'company',

                                                ],
                                            ],
                                        ],
                                        'Member Company Profile:General.Edit' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:General.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateMemberCompanyInfoForm',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/general',
                                                    'parents' => ['Member Company Profile:General.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateCompany',

                                                ],
                                                [
                                                    'name' => 'UpdateBasicInfoCompanyForm',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/general',
                                                    'parents' => ['Member Company Profile:General.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateCompany',

                                                ],
                                                [
                                                    'name' => 'UpdateSettingsCompanyForm',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/general',
                                                    'parents' => ['Member Company Profile:General.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateCompany',

                                                ],
                                                [
                                                    'name' => 'UpdateProfileDataCompanyForm',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/general',
                                                    'parents' => ['Member Company Profile:General.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateCompany',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Member Company Profile:Departments' => [
                                    'data' => [
                                        'name' => 'Member Company Profile:Departments',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 3,
                                    ],
                                    'list' => [
                                        'Member Company Profile:Departments.Enabled' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Departments.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetDepartmentsCompany',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/departments',
                                                    'type' => 'query',
                                                    'method' => 'departments',

                                                ],
                                            ],
                                        ],
                                        'Member Company Profile:Departments.Edit' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Departments.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateDepartment',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/departments',
                                                    'parents' => ['Member Company Profile:Departments.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateDepartment',

                                                ],
                                            ],
                                        ],
                                        'Member Company Profile:Departments.Add New Department' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Departments.Add New Department',
                                                'display_name' => 'Add New Department',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateDepartment',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/departments',
                                                    'parents' => ['Member Company Profile:Departments.Enabled', 'Member Company Profile:Departments.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'createDepartment',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Member Company Profile:Modules' => [
                                    'data' => [
                                        'name' => 'Member Company Profile:Modules',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 4,
                                    ],
                                    'list' => [
                                        'Member Company Profile:Modules.Enabled' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Modules.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetModules',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/modules',
                                                    'type' => 'query',
                                                    'method' => 'modules',

                                                ],
                                            ],
                                        ],
                                        'Member Company Profile:Modules.Edit' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Modules.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateApplicantCompanyModule',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/modules',
                                                    'parents' => ['Member Company Profile:Modules.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateCompanyModule',

                                                ],
                                            ],
                                        ],
                                        'Member Company Profile:Modules.Add Banking Module' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Modules.Add Banking Module',
                                                'display_name' => 'Add Banking Module',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'AddApplicantCompanyModule',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/module',
                                                    'parents' => ['Member Company Profile:Modules.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'addCompanyModule',

                                                ],
                                            ],
                                        ],
                                        'Member Company Profile:Modules.Edit Module Status' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Modules.Edit Module Status',
                                                'display_name' => 'Edit Module Status',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateApplicantCompanyModule',
                                                    'referer' => 'administration/member-info/full-profile/new/profile/modules',
                                                    'parents' => ['Member Company Profile:Modules.Enabled', 'Member Company Profile:Modules.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateCompanyModule',

                                                ],
                                            ],
                                        ],
                                        'Member Company Profile:Modules.Payment Provider List' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Modules.Payment Provider List',
                                                'display_name' => 'Payment Provider List',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Member Company Profile:Modules.IBAN Provider List' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Modules.IBAN Provider List',
                                                'display_name' => 'IBAN Provider List',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Member Company Profile:Projects List' => [
                                    'data' => [
                                        'name' => 'Member Company Profile:Projects List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 5,
                                    ],
                                    'list' => [
                                        'Member Company Profile:Projects List.Enabled' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Projects List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetProjects',
                                                    'referer' => 'administration/member-info/full-profile/new/projects/projects-list',
                                                    'type' => 'query',
                                                    'method' => 'projects',

                                                ],
                                            ],
                                        ],
                                        'Member Company Profile:Projects List.Add New Project' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Projects List.Add New Project',
                                                'display_name' => 'Add New Project',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateProject',
                                                    'referer' => 'administration/member-info/full-profile/new/projects/projects-list',
                                                    'parents' => ['Member Company Profile:Projects List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createProject',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Projects:General Settings' => [
                                    'data' => [
                                        'name' => 'Projects:General Settings',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 6,
                                    ],
                                    'list' => [
                                        'Projects:General Settings.Enabled' => [
                                            'data' => [
                                                'name' => 'Projects:General Settings.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetCommonProjectDataById',
                                                    'referer' => 'administration/member-info/full-profile/$id/projects/general-settings',
                                                    'type' => 'query',
                                                    'method' => 'project',

                                                ],
                                                [
                                                    'name' => 'GetSettingsProjectDataById',
                                                    'referer' => 'administration/member-info/full-profile/$id/projects/general-settings',
                                                    'type' => 'query',
                                                    'method' => 'project',

                                                ],
                                            ],
                                        ],
                                        'Projects:General Settings.Edit' => [
                                            'data' => [
                                                'name' => 'Projects:General Settings.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateProject',
                                                    'referer' => 'administration/member-info/full-profile/$id/projects/general-settings',
                                                    'parents' => ['Projects:General Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateProject',

                                                ],
                                            ],
                                        ],
                                        'Projects:General Settings.Basic Info' => [
                                            'data' => [
                                                'name' => 'Projects:General Settings.Basic Info',
                                                'display_name' => 'Basic Info',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Projects:General Settings.Default Settings for Client Registrations' => [
                                            'data' => [
                                                'name' => 'Projects:General Settings.Default Settings for Client Registrations',
                                                'display_name' => 'Default Settings for Client Registrations',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Projects:API Settings' => [
                                    'data' => [
                                        'name' => 'Projects:API Settings',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 7,
                                    ],
                                    'list' => [
                                        'Projects:API Settings.Enabled' => [
                                            'data' => [
                                                'name' => 'Projects:API Settings.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetProjectAPISettings',
                                                    'referer' => 'administration/member-info/full-profile/$id/projects/api-settings',
                                                    'type' => 'query',
                                                    'method' => 'project',

                                                ],
                                            ],
                                        ],
                                        'Projects:API Settings.Edit' => [
                                            'data' => [
                                                'name' => 'Projects:API Settings.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateProjectApiSettings',
                                                    'referer' => 'administration/member-info/full-profile/$id/projects/api-settings',
                                                    'parents' => ['Projects:API Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateProjectApiSetting',

                                                ],
                                            ],
                                        ],
                                        'Projects:API Settings.Payment Provider List' => [
                                            'data' => [
                                                'name' => 'Projects:API Settings.Payment Provider List',
                                                'display_name' => 'Payment Provider List',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Projects:API Settings.IBAN Provider List' => [
                                            'data' => [
                                                'name' => 'Projects:API Settings.IBAN Provider List',
                                                'display_name' => 'IBAN Provider List',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Member Company Profile:Member List' => [
                                    'data' => [
                                        'name' => 'Member Company Profile:Member List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 8,
                                    ],
                                    'list' => [
                                        'Member Company Profile:Member List.Enabled' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Member List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetMembersList',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/members-list',
                                                    'type' => 'query',
                                                    'method' => 'members',

                                                ],
                                            ],
                                        ],
                                        'Member Company Profile:Member List.Add New Member' => [
                                            'data' => [
                                                'name' => 'Member Company Profile:Member List.Add New Member',
                                                'display_name' => 'Add New Member',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateMember',
                                                    'referer' => 'administration/member-info/new-member/new-member',
                                                    'parents' => ['Member Company Profile:Member List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createMember',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Member Profile:General Settings' => [
                                    'data' => [
                                        'name' => 'Member Profile:General Settings',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 9,
                                    ],
                                    'list' => [
                                        'Member Profile:General Settings.Enabled' => [
                                            'data' => [
                                                'name' => 'Member Profile:General Settings.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Member Profile:General Settings.Edit' => [
                                            'data' => [
                                                'name' => 'Member Profile:General Settings.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateMember',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/general-settings',
                                                    'parents' => ['Member Profile:General Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateMember',

                                                ],
                                                [
                                                    'name' => 'UpdateMemberSettings',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/general-settings',
                                                    'parents' => ['Member Profile:General Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateMember',

                                                ],
                                            ],
                                        ],
                                        'Member Profile:General Settings.Basic Info' => [
                                            'data' => [
                                                'name' => 'Member Profile:General Settings.Basic Info',
                                                'display_name' => 'Basic Info',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetMemberBasicInfo',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/general-settings',
                                                    'parents' => ['Member Profile:General Settings.Enabled'],
                                                    'type' => 'query',
                                                    'method' => 'member',

                                                ],
                                            ],
                                        ],
                                        'Member Profile:General Settings.Settings' => [
                                            'data' => [
                                                'name' => 'Member Profile:General Settings.Settings',
                                                'display_name' => 'Settings',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetMemberSettings',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/general-settings',
                                                    'parents' => ['Member Profile:General Settings.Enabled'],
                                                    'type' => 'query',
                                                    'method' => 'member',

                                                ],
                                            ],
                                        ],
                                        'Member Profile:General Settings.Access Limitation' => [
                                            'data' => [
                                                'name' => 'Member Profile:General Settings.Access Limitation',
                                                'display_name' => 'Access Limitation',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetAccessLimitations',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/general-settings',
                                                    'parents' => ['Member Profile:General Settings.Enabled'],
                                                    'type' => 'query',
                                                    'method' => 'memberAccessLimitations',

                                                ],
                                            ],
                                        ],
                                        'Member Profile:General Settings.Add New Access' => [
                                            'data' => [
                                                'name' => 'Member Profile:General Settings.Add New Access',
                                                'display_name' => 'Add New Access',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateAccessLimitation',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/general-settings',
                                                    'parents' => ['Member Profile:General Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createMemberAccessLimitation',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Member:Security' => [
                                    'data' => [
                                        'name' => 'Member:Security',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 10,
                                    ],
                                    'list' => [
                                        'Member:Security.Enabled' => [
                                            'data' => [
                                                'name' => 'Member:Security.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetMemberTfaStatus',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/security/password',
                                                    'type' => 'query',
                                                    'method' => 'member',

                                                ],
                                                [
                                                    'name' => 'GetMemberTransaction',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/security/password',
                                                    'type' => 'query',
                                                    'method' => 'member',

                                                ],
                                            ],
                                        ],
                                        'Member:Security.Edit' => [
                                            'data' => [
                                                'name' => 'Member:Security.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                        ],
                                        'Member:Security.Password' => [
                                            'data' => [
                                                'name' => 'Member:Security.Password',
                                                'display_name' => 'Password',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'ChangeMemberPassword',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/security/password',
                                                    'parents' => ['Member:Security.Enabled', 'Member:Security.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'changeMemberPassword',

                                                ],
                                            ],
                                        ],
                                        'Member:Security.2FA' => [
                                            'data' => [
                                                'name' => 'Member:Security.2FA',
                                                'display_name' => '2FA',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Member:Security.Secure PIN' => [
                                            'data' => [
                                                'name' => 'Member:Security.Secure PIN',
                                                'display_name' => 'Secure PIN',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Member:Security.Security Settings' => [
                                            'data' => [
                                                'name' => 'Member:Security.Security Settings',
                                                'display_name' => 'Security Settings',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateMemberSecuritySettings',
                                                    'referer' => 'administration/member-info/full-profile/$id/members/security/password',
                                                    'parents' => ['Member:Security.Enabled', 'Member:Security.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateMember',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Logs:Active Session' => [
                                    'data' => [
                                        'name' => 'Logs:Active Session',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 11,
                                    ],
                                    'list' => [
                                        'Logs:Active Session.Enabled' => [
                                            'data' => [
                                                'name' => 'Logs:Active Session.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                    ],
                                ],
                                'Logs:Authentication Logs' => [
                                    'data' => [
                                        'name' => 'Logs:Authentication Logs',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 12,
                                    ],
                                    'list' => [
                                        'Logs:Authentication Logs.Enabled' => [
                                            'data' => [
                                                'name' => 'Logs:Authentication Logs.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'Email Templates' => [
                    'data' => [
                        'name' => 'Email Templates',
                        'is_active' => true,
                        'order' => 4,
                    ],
                    'list' => [
                        'member' => [
                            '' => [
                                'Email Templates:SMTP Details' => [
                                    'data' => [
                                        'name' => 'Email Templates:SMTP Details',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 1,
                                    ],
                                    'list' => [
                                        'Email Templates:SMTP Details.Enabled' => [
                                            'data' => [
                                                'name' => 'Email Templates:SMTP Details.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Email Templates:SMTP Details.Edit' => [
                                            'data' => [
                                                'name' => 'Email Templates:SMTP Details.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                        ],
                                        'Email Templates:SMTP Details.SMTP' => [
                                            'data' => [
                                                'name' => 'Email Templates:SMTP Details.SMTP',
                                                'display_name' => 'SMTP',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'EmailTemplateSmtpList',
                                                    'referer' => 'administration/email/smtp-templates',
                                                    'type' => 'query',
                                                    'method' => 'emailSmtps',

                                                ],
                                            ],
                                        ],
                                        'Email Templates:SMTP Details.Add New SMTP' => [
                                            'data' => [
                                                'name' => 'Email Templates:SMTP Details.Add New SMTP',
                                                'display_name' => 'Add New SMTP',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateSmtpDetails',
                                                    'referer' => 'administration/email/smtp-templates',
                                                    'parents' => ['Email Templates:SMTP Details.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createEmailSmtp',

                                                ],
                                            ],
                                        ],
                                        'Email Templates:SMTP Details.Delete SMTP Template' => [
                                            'data' => [
                                                'name' => 'Email Templates:SMTP Details.Delete SMTP Template',
                                                'display_name' => 'Delete SMTP Template',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'important',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'DeleteSmtpDetails',
                                                    'referer' => 'administration/email/smtp-templates',
                                                    'parents' => ['Email Templates:SMTP Details.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'deleteEmailSmtp',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Email Templates:Settings' => [
                                    'data' => [
                                        'name' => 'Email Templates:Settings',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 2,
                                    ],
                                    'list' => [
                                        'Email Templates:Settings.Enabled' => [
                                            'data' => [
                                                'name' => 'Email Templates:Settings.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'EmailTemplateItem',
                                                    'referer' => 'administration/email/email-template-settings',
                                                    'type' => 'query',
                                                    'method' => 'emailTemplate',

                                                ],
                                            ],
                                        ],
                                        'Email Templates:Settings.Edit' => [
                                            'data' => [
                                                'name' => 'Email Templates:Settings.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateEmailTemplate',
                                                    'referer' => 'administration/email/email-template-settings',
                                                    'parents' => ['Email Templates:Settings.Enabled'],
                                                    'type' => 'query',
                                                    'method' => 'updateEmailTemplate',

                                                ],
                                            ],
                                        ],
                                        'Email Templates:Settings.Type Notification: Client' => [
                                            'data' => [
                                                'name' => 'Email Templates:Settings.Type Notification: Client',
                                                'display_name' => 'Type Notification: Client',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Settings.Type Notification: Administration' => [
                                            'data' => [
                                                'name' => 'Email Templates:Settings.Type Notification: Administration',
                                                'display_name' => 'Type Notification: Administration',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Email Templates:Tags' => [
                                    'data' => [
                                        'name' => 'Email Templates:Tags',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 3,
                                    ],
                                    'list' => [
                                        'Email Templates:Tags.Banking: System' => [
                                            'data' => [
                                                'name' => 'Email Templates:Tags.Banking: System',
                                                'display_name' => 'Banking: System',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Tags.Banking: Common' => [
                                            'data' => [
                                                'name' => 'Email Templates:Tags.Banking: Common',
                                                'display_name' => 'Banking: Common',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Tags.Banking: Admin Notify' => [
                                            'data' => [
                                                'name' => 'Email Templates:Tags.Banking: Admin Notify',
                                                'display_name' => 'Banking: Admin Notify',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Tags.KYC: System' => [
                                            'data' => [
                                                'name' => 'Email Templates:Tags.KYC: System',
                                                'display_name' => 'KYC: System',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Tags.KYC: Common' => [
                                            'data' => [
                                                'name' => 'Email Templates:Tags.KYC: Common',
                                                'display_name' => 'KYC: Common',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Tags.KYC: Admin Notify' => [
                                            'data' => [
                                                'name' => 'Email Templates:Tags.KYC: Admin Notify',
                                                'display_name' => 'KYC: Admin Notify',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Email Templates:Notifications' => [
                                    'data' => [
                                        'name' => 'Email Templates:Notifications',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 4,
                                    ],
                                    'list' => [
                                        'Email Templates:Notifications.Enabled' => [
                                            'data' => [
                                                'name' => 'Email Templates:Notifications.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'EmailNotificationItem',
                                                    'referer' => 'administration/email/email-notifications',
                                                    'type' => 'query',
                                                    'method' => 'emailNotification',

                                                ],
                                            ],
                                        ],
                                        'Email Templates:Notifications.Edit' => [
                                            'data' => [
                                                'name' => 'Email Templates:Notifications.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateEmailNotification',
                                                    'referer' => 'administration/email/email-notifications',
                                                    'parents' => ['Email Templates:Notifications.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createEmailNotification',

                                                ],
                                            ],
                                        ],
                                        'Email Templates:Notifications.Recipient Type: Group' => [
                                            'data' => [
                                                'name' => 'Email Templates:Notifications.Recipient Type: Group',
                                                'display_name' => 'Recipient Type: Group',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Notifications.Recipient Type: Person' => [
                                            'data' => [
                                                'name' => 'Email Templates:Notifications.Recipient Type: Person',
                                                'display_name' => 'Recipient Type: Person',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Notifications.Group Type: Individual' => [
                                            'data' => [
                                                'name' => 'Email Templates:Notifications.Group Type: Individual',
                                                'display_name' => 'Group Type: Individual',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Notifications.Group Type: Corporate' => [
                                            'data' => [
                                                'name' => 'Email Templates:Notifications.Group Type: Corporate',
                                                'display_name' => 'Group Type: Corporate',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Email Templates:Notifications.Group Type: Member' => [
                                            'data' => [
                                                'name' => 'Email Templates:Notifications.Group Type: Member',
                                                'display_name' => 'Group Type: Member',
                                                'guard_name' => 'api',
                                                'order' => 7,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'Banking Module - Accounts' => [
                    'data' => [
                        'name' => 'Banking Module - Accounts',
                        'is_active' => true,
                        'order' => 5,
                    ],
                    'list' => [
                        'member' => [
                            '' => [
                                'Dashboard' => [
                                    'data' => [
                                        'name' => 'Dashboard',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 1,
                                    ],
                                    'list' => [
                                        'Dashboard.Enabled' => [
                                            'data' => [
                                                'name' => 'Dashboard.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Dashboard.Users Requests' => [
                                            'data' => [
                                                'name' => 'Dashboard.Users Requests',
                                                'display_name' => 'Users Requests',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Dashboard.Transfer Requests' => [
                                            'data' => [
                                                'name' => 'Dashboard.Transfer Requests',
                                                'display_name' => 'Transfer Requests',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Dashboard.Accounts Requests' => [
                                            'data' => [
                                                'name' => 'Dashboard.Accounts Requests',
                                                'display_name' => 'Accounts Requests',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Dashboard.Tickets' => [
                                            'data' => [
                                                'name' => 'Dashboard.Tickets',
                                                'display_name' => 'Tickets',
                                                'guard_name' => 'api',
                                                'order' => 5,
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
                                        'order' => 2,
                                    ],
                                    'list' => [
                                        'Account List.Enabled' => [
                                            'data' => [
                                                'name' => 'Account List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'AccountsList',
                                                    'referer' => 'banking/accounts/list',
                                                    'type' => 'query',
                                                    'method' => 'accountList',

                                                ],
                                            ],
                                        ],
                                        'Account List.Open Account' => [
                                            'data' => [
                                                'name' => 'Account List.Open Account',
                                                'display_name' => 'Open Account',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateAccount',
                                                    'referer' => 'banking/accounts/list',
                                                    'parents' => ['Account List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createAccount',

                                                ],
                                            ],
                                        ],
                                        'Account List.Export' => [
                                            'data' => [
                                                'name' => 'Account List.Export',
                                                'display_name' => 'Export',
                                                'guard_name' => 'api',
                                                'order' => 3,
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
                                        'order' => 3,
                                    ],
                                    'list' => [
                                        'Account Details.Enabled' => [
                                            'data' => [
                                                'name' => 'Account Details.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetAccountBalance',
                                                    'referer' => 'banking/accounts/details/$id',
                                                    'type' => 'query',
                                                    'method' => 'account',

                                                ],
                                                [
                                                    'name' => 'GetAccountCommissionLimits',
                                                    'referer' => 'banking/accounts/details/$id',
                                                    'type' => 'query',
                                                    'method' => 'commissionTemplate',

                                                ],
                                            ],
                                        ],
                                        'Account Details.Edit' => [
                                            'data' => [
                                                'name' => 'Account Details.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateAccount',
                                                    'referer' => 'banking/accounts/details/$id',
                                                    'parents' => ['Account Details.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateAccount',

                                                ],
                                            ],
                                        ],
                                        'Account Details.Account Details' => [
                                            'data' => [
                                                'name' => 'Account Details.Account Details',
                                                'display_name' => 'Account Details',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetAccount',
                                                    'referer' => 'banking/accounts/details/$id',
                                                    'parents' => ['Account Details.Enabled'],
                                                    'type' => 'query',
                                                    'method' => 'account',

                                                ],
                                            ],
                                        ],
                                        'Account Details.Generate IBAN' => [
                                            'data' => [
                                                'name' => 'Account Details.Generate IBAN',
                                                'display_name' => 'Generate IBAN',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Account Details.Show Balance' => [
                                            'data' => [
                                                'name' => 'Account Details.Show Balance',
                                                'display_name' => 'Show Balance',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Account Details.Make Transfer' => [
                                            'data' => [
                                                'name' => 'Account Details.Make Transfer',
                                                'display_name' => 'Make Transfer',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Account Details.Requisites' => [
                                            'data' => [
                                                'name' => 'Account Details.Requisites',
                                                'display_name' => 'Requisites',
                                                'guard_name' => 'api',
                                                'order' => 7,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Account Details.Statements' => [
                                            'data' => [
                                                'name' => 'Account Details.Statements',
                                                'display_name' => 'Statements',
                                                'guard_name' => 'api',
                                                'order' => 8,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Account Details.Export Statement' => [
                                            'data' => [
                                                'name' => 'Account Details.Export Statement',
                                                'display_name' => 'Export Statement',
                                                'guard_name' => 'api',
                                                'order' => 9,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Requisites' => [
                                    'data' => [
                                        'name' => 'Requisites',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 4,
                                    ],
                                    'list' => [
                                        'Requisites.Enabled' => [
                                            'data' => [
                                                'name' => 'Requisites.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'RequisiteItem',
                                                    'referer' => 'banking/accounts/requisites',
                                                    'type' => 'query',
                                                    'method' => 'requisite',

                                                ],
                                            ],
                                        ],
                                        'Requisites.Export' => [
                                            'data' => [
                                                'name' => 'Requisites.Export',
                                                'display_name' => 'Export',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'export',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'DownloadRequisiteDetails',
                                                    'referer' => 'banking/accounts/requisites',
                                                    'parents' => ['Requisites.Enabled'],
                                                    'type' => 'query',
                                                    'method' => 'downloadRequisiteDetails',

                                                ],
                                            ],
                                        ],
                                        'Requisites.Send Requisites' => [
                                            'data' => [
                                                'name' => 'Requisites.Send Requisites',
                                                'display_name' => 'Send Requisites',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'SendRequisiteDetails',
                                                    'referer' => 'banking/accounts/requisites',
                                                    'parents' => ['Requisites.Enabled'],
                                                    'type' => 'query',
                                                    'method' => 'sendRequisiteDetails',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Statement' => [
                                    'data' => [
                                        'name' => 'Statement',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 5,
                                    ],
                                    'list' => [
                                        'Statement.Enabled' => [
                                            'data' => [
                                                'name' => 'Statement.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Statement.Export' => [
                                            'data' => [
                                                'name' => 'Statement.Export',
                                                'display_name' => 'Export',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'export',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'Banking Module - Payments' => [
                    'data' => [
                        'name' => 'Banking Module - Payments',
                        'is_active' => true,
                        'order' => 6,
                    ],
                    'list' => [
                        'member' => [
                            '' => [
                                'Payments:IWT:List' => [
                                    'data' => [
                                        'name' => 'Payments:IWT:List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 1,
                                    ],
                                    'list' => [
                                        'Payments:IWT:List.Enabled' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Payments:IWT:List.Transfers Stats' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:List.Transfers Stats',
                                                'display_name' => 'Transfers Stats',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payments:IWT:List.IWT List' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:List.IWT List',
                                                'display_name' => 'IWT List',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payments:IWT:List.Export' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:List.Export',
                                                'display_name' => 'Export',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'export',
                                            ],
                                        ],
                                    ],
                                ],
                                'Payments:IWT:Details' => [
                                    'data' => [
                                        'name' => 'Payments:IWT:Details',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 2,
                                    ],
                                    'list' => [
                                        'Payments:IWT:Details.Enabled' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:Details.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Payments:IWT:Details.Edit' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:Details.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                        ],
                                        'Payments:IWT:Details.Upload Document' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:Details.Upload Document',
                                                'display_name' => 'Upload Document',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'export',
                                            ],
                                        ],
                                        'Payments:IWT:Details.Export' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:Details.Export',
                                                'display_name' => 'Export',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'export',
                                            ],
                                        ],
                                        'Payments:IWT:Details.PP Information' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:Details.PP Information',
                                                'display_name' => 'PP Information',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payments:IWT:Details.History' => [
                                            'data' => [
                                                'name' => 'Payments:IWT:Details.History',
                                                'display_name' => 'History',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Payments:OWT:List' => [
                                    'data' => [
                                        'name' => 'Payments:OWT:List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 3,
                                    ],
                                    'list' => [
                                        'Payments:OWT:List.Enabled' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Payments:OWT:List.Transfers Stats' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:List.Transfers Stats',
                                                'display_name' => 'Transfers Stats',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payments:OWT:List.OWT List' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:List.OWT List',
                                                'display_name' => 'OWT List',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payments:OWT:List.Export' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:List.Export',
                                                'display_name' => 'Export',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'export',
                                            ],
                                        ],
                                    ],
                                ],
                                'Payments:OWT:Details' => [
                                    'data' => [
                                        'name' => 'Payments:OWT:Details',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 4,
                                    ],
                                    'list' => [
                                        'Payments:OWT:Details.Enabled' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:Details.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Payments:OWT:Details.Edit' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:Details.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                        ],
                                        'Payments:OWT:Details.Upload Document' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:Details.Upload Document',
                                                'display_name' => 'Upload Document',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'export',
                                            ],
                                        ],
                                        'Payments:OWT:Details.Export' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:Details.Export',
                                                'display_name' => 'Export',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'export',
                                            ],
                                        ],
                                        'Payments:OWT:Details.PP Information' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:Details.PP Information',
                                                'display_name' => 'PP Information',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payments:OWT:Details.History' => [
                                            'data' => [
                                                'name' => 'Payments:OWT:Details.History',
                                                'display_name' => 'History',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Payments:Exchange:List' => [
                                    'data' => [
                                        'name' => 'Payments:Exchange:List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 5,
                                    ],
                                    'list' => [
                                        'Payments:Exchange:List.Enabled' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Payments:Exchange:List.Transfers Stats' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:List.Transfers Stats',
                                                'display_name' => 'Transfers Stats',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payments:Exchange:List.Exchange List' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:List.Exchange List',
                                                'display_name' => 'Exchange List',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payments:Exchange:List.Export' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:List.Export',
                                                'display_name' => 'Export',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'export',
                                            ],
                                        ],

                                    ],
                                ],
                                'Payments:Exchange:Details' => [
                                    'data' => [
                                        'name' => 'Payments:Exchange:Details',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 6,
                                    ],
                                    'list' => [
                                        'Payments:Exchange:Details.Enabled' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:Details.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Payments:Exchange:Details.Edit' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:Details.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                        ],
                                        'Payments:Exchange:Details.Upload Document' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:Details.Upload Document',
                                                'display_name' => 'Upload Document',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'export',
                                            ],
                                        ],
                                        'Payments:Exchange:Details.Export' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:Details.Export',
                                                'display_name' => 'Export',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'export',
                                            ],
                                        ],
                                        'Payments:Exchange:Details.PP Information' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:Details.PP Information',
                                                'display_name' => 'PP Information',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payments:Exchange:Details.History' => [
                                            'data' => [
                                                'name' => 'Payments:Exchange:Details.History',
                                                'display_name' => 'History',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Make Transfer' => [
                                    'data' => [
                                        'name' => 'Make Transfer',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 7,
                                    ],
                                    'list' => [
                                        'Make Transfer.Enabled' => [
                                            'data' => [
                                                'name' => 'Make Transfer.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Make Transfer.IWT' => [
                                            'data' => [
                                                'name' => 'Make Transfer.IWT',
                                                'display_name' => 'IWT',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Make Transfer.OWT' => [
                                            'data' => [
                                                'name' => 'Make Transfer.OWT',
                                                'display_name' => 'OWT',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Make Transfer.TBA' => [
                                            'data' => [
                                                'name' => 'Make Transfer.TBA',
                                                'display_name' => 'TBA',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Make Transfer.TBU' => [
                                            'data' => [
                                                'name' => 'Make Transfer.TBU',
                                                'display_name' => 'TBU',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Make Transfer.Exchange' => [
                                            'data' => [
                                                'name' => 'Make Transfer.Exchange',
                                                'display_name' => 'Exchange',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Make Transfer.Fee' => [
                                            'data' => [
                                                'name' => 'Make Transfer.Fee',
                                                'display_name' => 'Fee',
                                                'guard_name' => 'api',
                                                'order' => 7,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Sign Payments' => [
                                    'data' => [
                                        'name' => 'Sign Payments',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 8,
                                    ],
                                    'list' => [
                                        'Sign Payments.Enabled' => [
                                            'data' => [
                                                'name' => 'Sign Payments.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Sign Payments.IWT' => [
                                            'data' => [
                                                'name' => 'Sign Payments.IWT',
                                                'display_name' => 'IWT',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Sign Payments.OWT' => [
                                            'data' => [
                                                'name' => 'Sign Payments.OWT',
                                                'display_name' => 'OWT',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Sign Payments.TBA' => [
                                            'data' => [
                                                'name' => 'Sign Payments.TBA',
                                                'display_name' => 'TBA',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Sign Payments.TBU' => [
                                            'data' => [
                                                'name' => 'Sign Payments.TBU',
                                                'display_name' => 'TBU',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Sign Payments.Exchange' => [
                                            'data' => [
                                                'name' => 'Sign Payments.Exchange',
                                                'display_name' => 'Exchange',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Sign Payments.Fee' => [
                                            'data' => [
                                                'name' => 'Sign Payments.Fee',
                                                'display_name' => 'Fee',
                                                'guard_name' => 'api',
                                                'order' => 7,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'Banking Module - Commissions' => [
                    'data' => [
                        'name' => 'Banking Module - Commissions',
                        'is_active' => true,
                        'order' => 7,
                    ],
                    'list' => [
                        'member' => [
                            '' => [
                                'Commission Template:List' => [
                                    'data' => [
                                        'name' => 'Commission Template:List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 1,
                                    ],
                                    'list' => [
                                        'Commission Template:List.Enabled' => [
                                            'data' => [
                                                'name' => 'Commission Template:List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CommissionTemplatesList',
                                                    'referer' => 'banking/payment-provider/full-profile/commission-templates',
                                                    'type' => 'query',
                                                    'method' => 'commissionTemplates',

                                                ],
                                            ],
                                        ],
                                        'Commission Template:List.Add New Template' => [
                                            'data' => [
                                                'name' => 'Commission Template:List.Add New Template',
                                                'display_name' => 'Add New Template',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateCommissionTemplate',
                                                    'referer' => 'banking/payment-provider/full-profile/settings/create',
                                                    'parents' => ['Commission Template:List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createCommissionTemplate',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Commission Template:Settings' => [
                                    'data' => [
                                        'name' => 'Commission Template:Settings',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 2,
                                    ],
                                    'list' => [
                                        'Commission Template:Settings.Enabled' => [
                                            'data' => [
                                                'name' => 'Commission Template:Settings.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CommissionTemplatesFilterList',
                                                    'referer' => 'banking/payment-provider/full-profile/settings/create',
                                                    'type' => 'query',
                                                    'method' => 'commissionTemplates',

                                                ],
                                            ],
                                        ],
                                        'Commission Template:Settings.Edit' => [
                                            'data' => [
                                                'name' => 'Commission Template:Settings.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdateCommissionTemplate',
                                                    'referer' => 'banking/payment-provider/full-profile/settings/create',
                                                    'parents' => ['Commission Template:Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updateCommissionTemplate',

                                                ],
                                            ],
                                        ],
                                        'Commission Template:Settings.Thresholds' => [
                                            'data' => [
                                                'name' => 'Commission Template:Settings.Thresholds',
                                                'display_name' => 'Thresholds',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateCommissionTemplateLimit',
                                                    'referer' => 'banking/payment-provider/full-profile/settings/create',
                                                    'parents' => ['Commission Template:Settings.Enabled', 'Commission Template:Settings.Edit'],
                                                    'type' => 'mutation',
                                                    'method' => 'createCommissionTemplateLimit',

                                                ],
                                            ],
                                        ],
                                        'Commission Template:Settings.Delete' => [
                                            'data' => [
                                                'name' => 'Commission Template:Settings.Delete',
                                                'display_name' => 'Delete',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'important',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'DeleteCommissionTemplate',
                                                    'referer' => 'banking/payment-provider/full-profile/commission-templates',
                                                    'parents' => ['Commission Template:Settings.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'deleteCommissionTemplate',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Commission Template:Price Lists' => [
                                    'data' => [
                                        'name' => 'Commission Template:Price Lists',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 3,
                                    ],
                                    'list' => [
                                        'Commission Template:Price Lists.Enabled' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CommissionPriceLists',
                                                    'referer' => 'banking/payment-provider/full-profile/price-lists',
                                                    'type' => 'query',
                                                    'method' => 'commissionPriceLists',

                                                ],
                                            ],
                                        ],
                                        'Commission Template:Price Lists.Add New Price List' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists.Add New Price List',
                                                'display_name' => 'Add New Price List',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'add',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'CreateCommissionPriceList',
                                                    'referer' => 'banking/payment-provider/full-profile/price-lists',
                                                    'parents' => ['Commission Template:Price Lists.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'createCommissionPriceList',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Commission Template:Price Lists:Fees List' => [
                                    'data' => [
                                        'name' => 'Commission Template:Price Lists:Fees List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 4,
                                    ],
                                    'list' => [
                                        'Commission Template:Price Lists:Fees List.Enabled' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists:Fees List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'GetPriceListFees',
                                                    'referer' => 'banking/payment-provider/full-profile/fees',
                                                    'type' => 'query',
                                                    'method' => 'priceListFees',

                                                ],
                                            ],
                                        ],
                                        'Commission Template:Price Lists:Fees List.Edit' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists:Fees List.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'UpdatePriceListFee',
                                                    'referer' => 'banking/payment-provider/full-profile/fees',
                                                    'parents' => ['Commission Template:Price Lists:Fees List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'updatePriceListFees',

                                                ],
                                            ],
                                        ],
                                        'Commission Template:Price Lists:Fees List.Delete' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists:Fees List.Delete',
                                                'display_name' => 'Delete',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'important',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'DeletePriceListFee',
                                                    'referer' => 'banking/payment-provider/full-profile/fees',
                                                    'parents' => ['Commission Template:Price Lists:Fees List.Enabled'],
                                                    'type' => 'mutation',
                                                    'method' => 'deletePriceListFees',

                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'Commission Template:Price Lists:Add New Fee' => [
                                    'data' => [
                                        'name' => 'Commission Template:Price Lists:Add New Fee',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 5,
                                    ],
                                    'list' => [
                                        'Commission Template:Price Lists:Add New Fee.Enabled' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists:Add New Fee.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                            'operations' => [
                                                [
                                                    'name' => 'FeesPaymentSystems',
                                                    'referer' => 'banking/payment-provider/full-profile/fees',
                                                    'type' => 'query',
                                                    'method' => 'paymentSystems',

                                                ],
                                                [
                                                    'name' => 'GetFeeTypes',
                                                    'referer' => 'banking/payment-provider/full-profile/fees',
                                                    'type' => 'query',
                                                    'method' => 'feeTypes',

                                                ],
                                                [
                                                    'name' => 'GetFeeOperationTypes',
                                                    'referer' => 'banking/payment-provider/full-profile/fees',
                                                    'type' => 'query',
                                                    'method' => 'operationTypes',

                                                ],
                                            ],
                                        ],
                                        'Commission Template:Price Lists:Add New Fee.Service Fee' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists:Add New Fee.Service Fee',
                                                'display_name' => 'Service Fee',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Commission Template:Price Lists:Add New Fee.Exchange Fee' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists:Add New Fee.Exchange Fee',
                                                'display_name' => 'Exchange Fee',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Commission Template:Price Lists:Add New Fee.BTU Fee' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists:Add New Fee.BTU Fee',
                                                'display_name' => 'BTU Fee',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Commission Template:Price Lists:Add New Fee.BTA Fee' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists:Add New Fee.BTA Fee',
                                                'display_name' => 'BTA Fee',
                                                'guard_name' => 'api',
                                                'order' => 5,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Commission Template:Price Lists:Add New Fee.Transfers Fee' => [
                                            'data' => [
                                                'name' => 'Commission Template:Price Lists:Add New Fee.Transfers Fee',
                                                'display_name' => 'Transfers Fee',
                                                'guard_name' => 'api',
                                                'order' => 6,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'Banking Module - Core' => [
                    'data' => [
                        'name' => 'Banking Module - Core',
                        'is_active' => true,
                        'order' => 8,
                    ],
                    'list' => [
                        'member' => [
                            '' => [
                                'IBAN Provider:List' => [
                                    'data' => [
                                        'name' => 'IBAN Provider:List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 1,
                                    ],
                                    'list' => [
                                        'IBAN Provider:List.Enabled' => [
                                            'data' => [
                                                'name' => 'IBAN Provider:List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                    ],
                                ],
                                'IBAN Provider:Full Profile' => [
                                    'data' => [
                                        'name' => 'IBAN Provider:Full Profile',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 2,
                                    ],
                                    'list' => [
                                        'IBAN Provider:Full Profile.Enabled' => [
                                            'data' => [
                                                'name' => 'IBAN Provider:Full Profile.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'IBAN Provider:Full Profile.Edit' => [
                                            'data' => [
                                                'name' => 'IBAN Provider:Full Profile.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                        ],
                                        'IBAN Provider:Full Profile.Member Companies List' => [
                                            'data' => [
                                                'name' => 'IBAN Provider:Full Profile.Member Companies List',
                                                'display_name' => 'Member Companies List',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Global Settings:Transaction Region' => [
                                    'data' => [
                                        'name' => 'Global Settings:Transaction Region',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 3,
                                    ],
                                    'list' => [
                                        'Global Settings:Transaction Region.Enabled' => [
                                            'data' => [
                                                'name' => 'Global Settings:Transaction Region.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Global Settings:Transaction Region.Edit' => [
                                            'data' => [
                                                'name' => 'Global Settings:Transaction Region.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                        ],
                                        'Global Settings:Transaction Region.Add New Region' => [
                                            'data' => [
                                                'name' => 'Global Settings:Transaction Region.Add New Region',
                                                'display_name' => 'Add New Region',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'add',
                                            ],
                                        ],
                                    ],
                                ],
                                'Payment Provider:List' => [
                                    'data' => [
                                        'name' => 'Payment Provider:List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 4,
                                    ],
                                    'list' => [
                                        'Payment Provider:List.Enabled' => [
                                            'data' => [
                                                'name' => 'Payment Provider:List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                    ],
                                ],
                                'Payment Provider:Settings' => [
                                    'data' => [
                                        'name' => 'Payment Provider:Settings',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 5,
                                    ],
                                    'list' => [
                                        'Payment Provider:Settings.Enabled' => [
                                            'data' => [
                                                'name' => 'Payment Provider:Settings.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Payment Provider:Settings.Edit' => [
                                            'data' => [
                                                'name' => 'Payment Provider:Settings.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'edit',
                                            ],
                                        ],
                                        'Payment Provider:Settings.Payment System Details' => [
                                            'data' => [
                                                'name' => 'Payment Provider:Settings.Payment System Details',
                                                'display_name' => 'Payment System Details',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'info',
                                            ],
                                        ],
                                    ],
                                ],
                                'Payment System:List' => [
                                    'data' => [
                                        'name' => 'Payment System:List',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 6,
                                    ],
                                    'list' => [
                                        'Payment System:List.Enabled' => [
                                            'data' => [
                                                'name' => 'Payment System:List.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                    ],
                                ],
                                'Payment System:Full Profile' => [
                                    'data' => [
                                        'name' => 'Payment System:Full Profile',
                                        'type' => 'member',
                                        'separator' => null,
                                        'order' => 7,
                                    ],
                                    'list' => [
                                        'Payment System:Full Profile.Enabled' => [
                                            'data' => [
                                                'name' => 'Payment System:Full Profile.Enabled',
                                                'display_name' => 'Enabled',
                                                'guard_name' => 'api',
                                                'order' => 1,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Payment System:Full Profile.Edit' => [
                                            'data' => [
                                                'name' => 'Payment System:Full Profile.Edit',
                                                'display_name' => 'Edit',
                                                'guard_name' => 'api',
                                                'order' => 2,
                                                'type' => 'read',
                                            ],
                                        ],
                                        'Payment System:Full Profile.Delete' => [
                                            'data' => [
                                                'name' => 'Payment System:Full Profile.Delete',
                                                'display_name' => 'Delete',
                                                'guard_name' => 'api',
                                                'order' => 3,
                                                'type' => 'important',
                                            ],
                                        ],
                                        'Payment System:Full Profile.Banks Correspondent' => [
                                            'data' => [
                                                'name' => 'Payment System:Full Profile.Banks Correspondent',
                                                'display_name' => 'Banks Correspondent',
                                                'guard_name' => 'api',
                                                'order' => 4,
                                                'type' => 'info',
                                            ],
                                        ],
                                        'Payment System:Full Profile.Add New Corr Bank' => [
                                            'data' => [
                                                'name' => 'Payment System:Full Profile.Add New Corr Bank',
                                                'display_name' => 'Add New Corr Bank',
                                                'guard_name' => 'api',
                                                'order' => 5,
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
                                unset($permission['data']['order']);

                                /** @var Permissions $p */
                                $p = Permissions::firstOrCreate($permission['data']);
                                $p->order = $order;

                                $p->save();

                                foreach ($permission['operations'] ?? [] as $o) {
                                    /** @var PermissionOperation $operation */
                                    $operation = PermissionOperation::query()->updateOrCreate(
                                        ['name' => $o['name'], 'referer' => $o['referer'],],
                                        ['name' => $o['name'], 'referer' => $o['referer'], 'method' => $o['method'], 'type' => $o['type']]
                                    );
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

            $filters = [
                //            [
                //                'mode' => PermissionFilter::SCOPE_MODE,
                //                'action' => null,
                //                'table' => 'email_templates',
                //                'column' => 'service_type',
                //                'value' => 'banking',
                //                'binds' => ['Email Templates:Tag.Administration: Banking'],
                //            ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_notifications',
                    'column' => 'recipient_type',
                    'value' => EmailNotification::RECIPIENT_GROUP,
                    'binds' => [
                        'Email Templates:Notifications.Enabled',
                        'Email Templates:Notifications.Edit',
                        'Email Templates:Notifications.Recipient Type: Group',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_notifications',
                    'column' => 'recipient_type',
                    'value' => EmailNotification::RECIPIENT_PERSON,
                    'binds' => [
                        'Email Templates:Notifications.Enabled',
                        'Email Templates:Notifications.Edit',
                        'Email Templates:Notifications.Recipient Type: Person',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_notifications',
                    'column' => 'group_type_id',
                    'value' => GroupTypeEnum::INDIVIDUAL->value,
                    'binds' => [
                        'Email Templates:Notifications.Enabled',
                        'Email Templates:Notifications.Edit',
                        'Email Templates:Notifications.Group Type: Individual',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_notifications',
                    'column' => 'group_type_id',
                    'value' => GroupTypeEnum::COMPANY->value,
                    'binds' => [
                        'Email Templates:Notifications.Enabled',
                        'Email Templates:Notifications.Edit',
                        'Email Templates:Notifications.Group Type: Corporate',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_notifications',
                    'column' => 'group_type_id',
                    'value' => GroupTypeEnum::MEMBER->value,
                    'binds' => [
                        'Email Templates:Notifications.Enabled',
                        'Email Templates:Notifications.Edit',
                        'Email Templates:Notifications.Group Type: Member',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'fees',
                    'column' => 'fee_type_id',
                    'value' => FeeTypeEnum::SERVICE_FEE->value,
                    'binds' => [
                        'Commission Template:Price Lists:Add New Fee.Enabled',
                        'Commission Template:Price Lists:Add New Fee.Service Fee',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'fees',
                    'column' => 'fee_type_id',
                    'value' => FeeTypeEnum::EXCHANGE_FEE->value,
                    'binds' => [
                        'Commission Template:Price Lists:Add New Fee.Enabled',
                        'Commission Template:Price Lists:Add New Fee.Exchange Fee',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'fees',
                    'column' => 'fee_type_id',
                    'value' => FeeTypeEnum::EXCHANGE_FEE->value,
                    'binds' => [
                        'Commission Template:Price Lists:Add New Fee.Enabled',
                        'Commission Template:Price Lists:Add New Fee.Exchange Fee',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'fees',
                    'column' => 'fee_type_id',
                    'value' => FeeTypeEnum::BTU_FEE->value,
                    'binds' => [
                        'Commission Template:Price Lists:Add New Fee.Enabled',
                        'Commission Template:Price Lists:Add New Fee.BTU Fee',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'fees',
                    'column' => 'fee_type_id',
                    'value' => FeeTypeEnum::BTA_FEE->value,
                    'binds' => [
                        'Commission Template:Price Lists:Add New Fee.Enabled',
                        'Commission Template:Price Lists:Add New Fee.BTA Fee',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'fees',
                    'column' => 'fee_type_id',
                    'value' => FeeTypeEnum::TRANSFERS_FEE->value,
                    'binds' => [
                        'Commission Template:Price Lists:Add New Fee.Enabled',
                        'Commission Template:Price Lists:Add New Fee.Transfers Fee',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'roles',
                    'column' => 'group_type_id',
                    'value' => GroupTypeEnum::INDIVIDUAL->value,
                    'binds' => [
                        'Role List.Add New Role',
                        'Roles Settings.Group Type: Individual',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'roles',
                    'column' => 'group_type_id',
                    'value' => GroupTypeEnum::COMPANY->value,
                    'binds' => [
                        'Role List.Add New Role',
                        'Roles Settings.Group Type: Corporate',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'roles',
                    'column' => 'group_type_id',
                    'value' => GroupTypeEnum::MEMBER->value,
                    'binds' => [
                        'Role List.Add New Role',
                        'Roles Settings.Group Type: Member',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'group_role',
                    'column' => 'group_type_id',
                    'value' => GroupTypeEnum::INDIVIDUAL->value,
                    'binds' => [
                        'Groups List.Add New Group',
                        'Groups Settings.Group Type: Individual',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'group_role',
                    'column' => 'group_type_id',
                    'value' => GroupTypeEnum::COMPANY->value,
                    'binds' => [
                        'Groups List.Add New Group',
                        'Groups Settings.Group Type: Corporate',
                    ],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'group_role',
                    'column' => 'group_type_id',
                    'value' => GroupTypeEnum::MEMBER->value,
                    'binds' => [
                        'Groups List.Add New Group',
                        'Groups Settings.Group Type: Member',
                    ],
                ],
                [
                    'mode' => PermissionFilter::SCOPE_MODE,
                    'action' => null,
                    'table' => 'email_templates',
                    'column' => 'type',
                    'value' => EmailTemplatesTypeEnum::CLIENT,
                    'binds' => ['Email Templates:Settings.Enabled', 'Email Templates:Settings.Type Notification: Client'],
                ],
                [
                    'mode' => PermissionFilter::SCOPE_MODE,
                    'action' => null,
                    'table' => 'email_templates',
                    'column' => 'type',
                    'value' => EmailTemplatesTypeEnum::ADMINISTRATION,
                    'binds' => ['Email Templates:Settings.Enabled', 'Email Templates:Settings.Type Notification: Administration'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::BANKING_SYSTEM,
                    'binds' => ['Email Templates:Tags.Banking: System'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_UPDATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::BANKING_SYSTEM,
                    'binds' => ['Email Templates:Tags.Banking: System'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::BANKING_COMMON,
                    'binds' => ['Email Templates:Tags.Banking: Common'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_UPDATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::BANKING_COMMON,
                    'binds' => ['Email Templates:Tags.Banking: Common'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::BANKING_ADMIN_NOTIFY,
                    'binds' => ['Email Templates:Tags.Banking: Admin Notify'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_UPDATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::BANKING_ADMIN_NOTIFY,
                    'binds' => ['Email Templates:Tags.Banking: Admin Notify'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::KYC_SYSTEM,
                    'binds' => ['Email Templates:Tags.KYC: System'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_UPDATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::KYC_SYSTEM,
                    'binds' => ['Email Templates:Tags.KYC: System'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::KYC_COMMON,
                    'binds' => ['Email Templates:Tags.KYC: Common'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_UPDATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::KYC_COMMON,
                    'binds' => ['Email Templates:Tags.KYC: Common'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_CREATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::KYC_ADMIN_NOTIFY,
                    'binds' => ['Email Templates:Tags.KYC: Admin Notify'],
                ],
                [
                    'mode' => PermissionFilter::EVENT_MODE,
                    'action' => PermissionFilter::EVENT_UPDATING,
                    'table' => 'email_templates',
                    'column' => 'service_type',
                    'value' => ModuleTagEnum::KYC_ADMIN_NOTIFY,
                    'binds' => ['Email Templates:Tags.KYC: Admin Notify'],
                ],
            ];
            $lists = PermissionsList::where('type', 'member')->get()->pluck('id')->toArray();
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
                        throw new Exception("Not found bind permission in {$perName} filter");
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
