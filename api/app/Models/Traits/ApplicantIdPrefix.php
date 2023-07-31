<?php

namespace App\Models\Traits;

use App\Enums\ApplicantTypeEnum;
use App\Models\Account;
use App\Models\AccountClient;
use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantCompany;
use App\Models\ApplicantCompanyModules;
use App\Models\ApplicantDocument;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualCompany;
use App\Models\ApplicantIndividualModules;
use App\Models\ApplicantIndividualNotes;
use App\Models\ApplicantModuleActivity;
use App\Models\ApplicantRiskLevelHistory;
use App\Models\ClientIpAddress;
use App\Models\EmailNotificationClient;
use App\Models\EmailVerification;
use App\Models\Fee;
use App\Models\GroupRoleUser;
use App\Models\KycTimeline;
use App\Models\ProjectSettings;
use App\Models\Ticket;
use App\Models\TicketComments;
use App\Models\TransferExchange;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Database\Eloquent\Model;

trait ApplicantIdPrefix
{
    private const COLUMNS = [
        ApplicantCompany::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'owner_id' => true,
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'id' => true,
            ],
        ],
        ApplicantIndividual::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'id' => true,
            ],
        ],
        ApplicantCompanyModules::class => [
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'applicant_company_id' => true,
            ],
        ],
        ApplicantIndividualModules::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'applicant_individual_id' => true,
            ],
        ],
        ApplicantIndividualNotes::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'applicant_individual_id' => true,
            ],
        ],
        ApplicantDocument::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'applicant_id' => [
                    [
                        'column' => 'applicant_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'applicant_id' => [
                    [
                        'column' => 'applicant_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        ApplicantIndividualCompany::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'applicant_id' => [
                    [
                        'column' => 'applicant_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'applicant_id' => [
                    [
                        'column' => 'applicant_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
                'applicant_company_id' => true,
            ],
        ],
        ApplicantModuleActivity::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'applicant_id' => true,
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'applicant_id' => true,
            ],
        ],
        ApplicantRiskLevelHistory::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'applicant_id' => true,
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'applicant_id' => true,
            ],
        ],
        ApplicantBankingAccess::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'applicant_id' => true,
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'applicant_id' => true,
                'applicant_company_id' => true,
            ],
        ],
        KycTimeline::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'applicant_id' => [
                    [
                        'column' => 'applicant_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'applicant_id' => [
                    [
                        'column' => 'applicant_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        TransferExchange::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        TicketComments::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'client_id' => true,
            ],
        ],
        Ticket::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'client_id' => true,
            ],
        ],
        EmailVerification::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'client_id' => true,
            ],
        ],
        EmailNotificationClient::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        ClientIpAddress::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
        ],
        AccountClient::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        Account::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
                'owner_id' => true
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        TransferIncoming::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'recipient_id' => [
                    [
                        'column' => 'recipient_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'recipient_id' => [
                    [
                        'column' => 'recipient_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        TransferOutgoing::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'sender_id' => [
                    [
                        'column' => 'sender_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'sender_id' => [
                    [
                        'column' => 'sender_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        ProjectSettings::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'sender_id' => [
                    [
                        'column' => 'sender_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'sender_id' => [
                    [
                        'column' => 'sender_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        GroupRoleUser::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'user_id' => [
                    [
                        'column' => 'user_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'user_id' => [
                    [
                        'column' => 'user_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
        Fee::class => [
            '(' . ApplicantIndividual::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantIndividual'
                    ]
                ],
            ],
            '(' . ApplicantCompany::ID_PREFIX . ')' => [
                'client_id' => [
                    [
                        'column' => 'client_type',
                        'type' => 'call_user_func',
                        'value' => self::class . '::getApplicantCompany'
                    ]
                ],
            ],
        ],
    ];


    protected static function checkAndOverwriteIdPrefix(Model $model): void
    {
        if (isset(self::COLUMNS[get_class($model)])) {
            $object = self::COLUMNS[get_class($model)];
            foreach ($object as $check => $data) {
                foreach ($data as $column => $v) {
                    if (isset($model->{$column})) {
                        if (preg_match("/^({$check})([0-9]+)$/", $model->{$column}, $matches)) {
                            $model->{$column} = (int)last($matches);
                            if (is_array($v)) {
                                foreach ($v as $bind) {
                                    if ($bind['type'] == 'call_user_func')
                                        $model->{$bind['column']} = call_user_func($bind['value']);
                                    else
                                        $model->{$bind['column']} = $bind['value'];
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    private static function getApplicantIndividual(): string
    {
        return ApplicantTypeEnum::INDIVIDUAL->toString();
    }

    private static function getApplicantCompany(): string
    {
        return ApplicantTypeEnum::COMPANY->toString();
    }

}
