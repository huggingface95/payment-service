<?php

namespace App\Models\Traits;

use App\Enums\ApplicantTypeEnum;
use App\Models\Account;
use App\Models\AccountClient;
use App\Models\ApplicantCompany;
use App\Models\ApplicantCompanyModules;
use App\Models\ApplicantDocument;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualCompany;
use App\Models\ApplicantIndividualModules;
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
            'columns' => ['owner_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')'],
        ],
        ApplicantCompanyModules::class => [
            'columns' => ['applicant_company_id'],
            'checks' => ['(' . ApplicantCompany::ID_PREFIX . ')'],
        ],
        ApplicantIndividualModules::class => [
            'columns' => ['applicant_individual_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')'],
        ],
        ApplicantDocument::class => [
            'columns' => ['applicant_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'applicant_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        ApplicantIndividualCompany::class => [
            'columns' => ['applicant_id', 'applicant_company_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'applicant_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        ApplicantModuleActivity::class => [
            'columns' => ['applicant_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
        ],
        ApplicantRiskLevelHistory::class => [
            'columns' => ['applicant_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
        ],
        KycTimeline::class => [
            'columns' => ['applicant_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'applicant_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        TransferExchange::class => [
            'columns' => ['client_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'client_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        TicketComments::class => [
            'columns' => ['client_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')'],
        ],
        Ticket::class => [
            'columns' => ['client_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')'],
        ],
        EmailVerification::class => [
            'columns' => ['client_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')'],
        ],
        EmailNotificationClient::class => [
            'columns' => ['client_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'client_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        ClientIpAddress::class => [
            'columns' => ['client_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'client_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                    ]
                ]
            ]
        ],
        AccountClient::class => [
            'columns' => ['client_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'client_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        Account::class => [
            'columns' => ['client_id', 'owner_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'client_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        TransferIncoming::class => [
            'columns' => ['recipient_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'recipient_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        TransferOutgoing::class => [
            'columns' => ['sender_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'sender_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        ProjectSettings::class => [
            'columns' => ['sender_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'sender_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        GroupRoleUser::class => [
            'columns' => ['user_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'user_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
        Fee::class => [
            'columns' => ['client_id'],
            'checks' => ['(' . ApplicantIndividual::ID_PREFIX . ')', '(' . ApplicantCompany::ID_PREFIX . ')'],
            'binds' => [
                [
                    'column' => 'client_type',
                    'type' => 'call_user_func',
                    'enum' => [
                        ApplicantIndividual::ID_PREFIX => self::class . '::getApplicantIndividual',
                        ApplicantCompany::ID_PREFIX => self::class . '::getApplicantCompany',
                    ]
                ]
            ]
        ],
    ];


    protected static function checkAndOverwriteIdPrefix(Model $model): void
    {
        if (isset(self::COLUMNS[get_class($model)])) {
            $object = self::COLUMNS[get_class($model)];
            foreach ($object['columns'] as $column){
                if (isset($model->{$column})){
                    $regexp = sprintf("/^(?:%s)([0-9]+)$/", implode('|', $object['checks']));
                    if (preg_match($regexp, $model->{$column}, $matches)) {
                        $model->{$column} = (int)last($matches);
                        if (isset($object['binds'])) {
                            $prefix = null;
                            for ($i = 1; $i < count($object['checks'])+1; $i++){
                                if (strlen($matches[$i])){
                                    $prefix = $matches[$i];
                                    break;
                                }
                            }
                            foreach ($object['binds'] as $bind) {
                                if ($bind['type'] == 'call_user_func')
                                    $model->{$bind['column']} = call_user_func($bind['enum'][$prefix]);
                                else
                                    $model->{$bind['column']} = $bind['enum'][$prefix];
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
