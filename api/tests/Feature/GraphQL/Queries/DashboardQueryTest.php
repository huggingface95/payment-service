<?php

namespace Feature\GraphQL\Queries;

use App\Enums\ModuleEnum;
use App\Enums\OperationTypeEnum;
use App\Models\Account;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Ticket;
use App\Models\TransferBetweenAccount;
use App\Models\TransferBetweenUser;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardQueryTest extends TestCase
{
    public function testQueryDashboardAccountsStatisticNoAuth(): void
    {
        $this->graphQL('
            {
                dashboardAccountsStatistic  {
                    account_state_id
                    payment_provider_id
                    payment_provider_name
                    account_state_name
                    count
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryDashboardAccountsStatistic(): void
    {
        $statistic = Account::select([
            'account_states.name as account_state_name', 'payment_provider.name as payment_provider_name',
            'payment_provider_id', 'account_state_id', DB::raw('count(account_state_id) as count'),
        ])
            ->join('account_states', 'accounts.account_state_id', '=', 'account_states.id')
            ->join('payment_provider', 'accounts.payment_provider_id', '=', 'payment_provider.id')
            ->groupBy(['payment_provider.name', 'payment_provider_id', 'account_state_id', 'account_states.name'])
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    dashboardAccountsStatistic  {
                        account_state_id
                        payment_provider_id
                        payment_provider_name
                        account_state_name
                        count
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'dashboardAccountsStatistic' => [[
                    'account_state_id' => (string) $statistic->account_state_id,
                    'payment_provider_id' => (string) $statistic->payment_provider_id,
                    'payment_provider_name' => (string) $statistic->payment_provider_name,
                    'account_state_name' => (string) $statistic->account_state_name,
                    'count' => $statistic->count,
                ]],
            ],
        ]);
    }

    public function testQueryDashboardAccountsStatisticWithPaymentProviderId(): void
    {
        $statistic = Account::select([
            'account_states.name as account_state_name', 'payment_provider.name as payment_provider_name',
            'payment_provider_id', 'account_state_id', DB::raw('count(account_state_id) as count'),
        ])
            ->join('account_states', 'accounts.account_state_id', '=', 'account_states.id')
            ->join('payment_provider', 'accounts.payment_provider_id', '=', 'payment_provider.id')
            ->groupBy(['payment_provider.name', 'payment_provider_id', 'account_state_id', 'account_states.name'])
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query DashboardAccountsStatistic ($id: ID) {
                    dashboardAccountsStatistic (payment_provider_id: $id)  {
                        account_state_id
                        payment_provider_id
                        payment_provider_name
                        account_state_name
                        count
                    }
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'dashboardAccountsStatistic' => [[
                    'account_state_id' => (string) $statistic->account_state_id,
                    'payment_provider_id' => (string) $statistic->payment_provider_id,
                    'payment_provider_name' => (string) $statistic->payment_provider_name,
                    'account_state_name' => (string) $statistic->account_state_name,
                    'count' => $statistic->count,
                ]],
            ],
        ]);
    }

    public function testQueryDashboardTicketsStatistic(): void
    {
        $statistic = Ticket::select(['ticket_statuses.name as status_name', 'status as status_id', DB::raw('count(status) as count')])
            ->join('ticket_statuses', 'tickets.status', '=', 'ticket_statuses.id')
            ->groupBy(['status', 'ticket_statuses.name'])
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    dashboardTicketsStatistic {
                        status_id
                        status_name
                        count
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'status_id' => (string) $statistic->status_id,
            'status_name' => (string) $statistic->status_name,
            'count' => $statistic->count,
        ]);
    }

    public function testQueryDashboardTransferTicketsStatistic(): void
    {
        $transferIncomingStatistic = TransferIncoming::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferIncoming::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_incomings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::INCOMING_WIRE_TRANSFER->value)
            ->groupBy(['status_name']);

        $transferOutgoingStatistic = TransferOutgoing::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferOutgoing::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value)
            ->groupBy(['status_name']);

        $transferBetweenUserStatistic = TransferBetweenUser::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenUser::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $transferBetweenAccountStatistic = TransferBetweenAccount::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenAccount::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $statistic = array_merge(
            $transferIncomingStatistic->get()->toArray(),
            $transferOutgoingStatistic->get()->toArray(),
            $transferBetweenUserStatistic->get()->toArray(),
            $transferBetweenAccountStatistic->get()->toArray(),
        );

        $this->postGraphQL(
            [
                'query' => '
                {
                    dashboardTransfersStatistic {
                        transfer_type
                        status_name
                        count
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'transfer_type' => (string) $statistic[0]['transfer_type'],
            'status_name' => (string) $statistic[0]['status_name'],
            'count' => $statistic[0]['count'],
        ]);
    }

    public function testQueryTransferDashboardTicketsStatisticWithCompanyId(): void
    {
        $transferIncomingStatistic = TransferIncoming::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferIncoming::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_incomings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::INCOMING_WIRE_TRANSFER->value)
            ->groupBy(['status_name']);

        $transferOutgoingStatistic = TransferOutgoing::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferOutgoing::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value)
            ->groupBy(['status_name']);

        $transferBetweenUserStatistic = TransferBetweenUser::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenUser::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $transferBetweenAccountStatistic = TransferBetweenAccount::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenAccount::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $statistic = array_merge(
            $transferIncomingStatistic->get()->toArray(),
            $transferOutgoingStatistic->get()->toArray(),
            $transferBetweenUserStatistic->get()->toArray(),
            $transferBetweenAccountStatistic->get()->toArray(),
        );

        $this->postGraphQL(
            [
                'query' => '
                query DashboardTransfersStatistic ($id: ID) {
                    dashboardTransfersStatistic (company_id: $id) {
                        transfer_type
                        status_name
                        count
                    }
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'transfer_type' => (string) $statistic[0]['transfer_type'],
            'status_name' => (string) $statistic[0]['status_name'],
            'count' => $statistic[0]['count'],
        ]);
    }

    public function testQueryTransferDashboardTicketsStatisticWithPaymentBankId(): void
    {
        $transferIncomingStatistic = TransferIncoming::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferIncoming::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_incomings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::INCOMING_WIRE_TRANSFER->value)
            ->where('payment_bank_id', 1)
            ->groupBy(['status_name']);

        $transferOutgoingStatistic = TransferOutgoing::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferOutgoing::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value)
            ->where('payment_bank_id', 1)
            ->groupBy(['status_name']);

        $transferBetweenUserStatistic = TransferBetweenUser::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenUser::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $transferBetweenAccountStatistic = TransferBetweenAccount::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenAccount::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $statistic = array_merge(
            $transferIncomingStatistic->get()->toArray(),
            $transferOutgoingStatistic->get()->toArray(),
            $transferBetweenUserStatistic->get()->toArray(),
            $transferBetweenAccountStatistic->get()->toArray(),
        );

        $this->postGraphQL(
            [
                'query' => '
                query DashboardTransfersStatistic ($id: ID) {
                    dashboardTransfersStatistic (payment_bank_id: $id) {
                        transfer_type
                        status_name
                        count
                    }
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'transfer_type' => (string) $statistic[0]['transfer_type'],
            'status_name' => (string) $statistic[0]['status_name'],
            'count' => $statistic[0]['count'],
        ]);
    }

    public function testQueryDashboardTransferTicketsStatisticWithPaymentProviderId(): void
    {
        $transferIncomingStatistic = TransferIncoming::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferIncoming::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_incomings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::INCOMING_WIRE_TRANSFER->value)
            ->groupBy(['status_name']);

        $transferOutgoingStatistic = TransferOutgoing::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferOutgoing::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value)
            ->groupBy(['status_name']);

        $transferBetweenUserStatistic = TransferBetweenUser::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenUser::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $transferBetweenAccountStatistic = TransferBetweenAccount::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenAccount::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $statistic = array_merge(
            $transferIncomingStatistic->get()->toArray(),
            $transferOutgoingStatistic->get()->toArray(),
            $transferBetweenUserStatistic->get()->toArray(),
            $transferBetweenAccountStatistic->get()->toArray(),
        );

        $this->postGraphQL(
            [
                'query' => '
                query DashboardTransfersStatistic ($id: ID) {
                    dashboardTransfersStatistic (payment_provider_id: $id) {
                        transfer_type
                        status_name
                        count
                    }
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'transfer_type' => (string) $statistic[0]['transfer_type'],
            'status_name' => (string) $statistic[0]['status_name'],
            'count' => $statistic[0]['count'],
        ]);
    }

    public function testQueryDashboardTransferTicketsStatisticWithPaymentSystemId(): void
    {
        $transferIncomingStatistic = TransferIncoming::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferIncoming::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_incomings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::INCOMING_WIRE_TRANSFER->value)
            ->groupBy(['status_name']);

        $transferOutgoingStatistic = TransferOutgoing::select([
            'payment_status.name as status_name',
            DB::raw("'".class_basename(TransferOutgoing::class)."' as transfer_type, count(status_id) as count"),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->where('operation_type_id', OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value)
            ->groupBy(['status_name']);

        $transferBetweenUserStatistic = TransferBetweenUser::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenUser::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $transferBetweenAccountStatistic = TransferBetweenAccount::select([
            'status_name',
            DB::raw("'".class_basename(TransferBetweenAccount::class)."' as transfer_type, count(status_id) as count"),
        ])->groupBy(['status_name']);

        $statistic = array_merge(
            $transferIncomingStatistic->get()->toArray(),
            $transferOutgoingStatistic->get()->toArray(),
            $transferBetweenUserStatistic->get()->toArray(),
            $transferBetweenAccountStatistic->get()->toArray(),
        );

        $this->postGraphQL(
            [
                'query' => '
                query DashboardTransfersStatistic ($id: ID) {
                    dashboardTransfersStatistic (payment_system_id: $id) {
                        transfer_type
                        status_name
                        count
                    }
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'transfer_type' => (string) $statistic[0]['transfer_type'],
            'status_name' => (string) $statistic[0]['status_name'],
            'count' => $statistic[0]['count'],
        ]);
    }

    public function testQueryDashboardUsersStatistic(): void
    {
        $applicantIndividual = ApplicantIndividual::select([
            DB::raw("'".class_basename(ApplicantIndividual::class)."' as applicant_type,
            CASE applicant_individual_modules.is_active
                WHEN 'true' THEN 'Active'
                WHEN 'false' THEN 'Panding'
            END as status_name,
            count(applicant_individual_modules.is_active) as count"),
        ])
            ->join('applicant_individual_modules', 'applicant_individual.id', '=', 'applicant_individual_modules.applicant_individual_id')
            ->where('applicant_individual_modules.module_id', ModuleEnum::BANKING->value)
            ->groupBy(['applicant_individual_modules.is_active']);

        $applicantCompany = ApplicantCompany::select([
            DB::raw("'".class_basename(ApplicantCompany::class)."' as applicant_type,
            CASE applicant_company_modules.is_active
                WHEN 'true' THEN 'Active'
                WHEN 'false' THEN 'Panding'
            END as status_name,
            count(applicant_company_modules.is_active) as count"),
        ])
            ->join('applicant_company_modules', 'applicant_companies.id', '=', 'applicant_company_modules.applicant_company_id')
            ->where('applicant_company_modules.module_id', ModuleEnum::BANKING->value)
            ->groupBy(['applicant_company_modules.is_active']);

        $statistic = array_merge(
            $applicantIndividual->get()->toArray(),
            $applicantCompany->get()->toArray(),
        );

        $this->postGraphQL(
            [
                'query' => '
                {
                    dashboardUsersStatistic {
                        applicant_type
                        status_name
                        count
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'applicant_type' => (string) $statistic[0]['applicant_type'],
            'status_name' => (string) $statistic[0]['status_name'],
            'count' => $statistic[0]['count'],
        ]);
    }

    public function testQueryDashboardUsersStatisticWithCompanyId(): void
    {
        $applicantIndividual = ApplicantIndividual::select([
            DB::raw("'".class_basename(ApplicantIndividual::class)."' as applicant_type,
            CASE applicant_individual_modules.is_active
                WHEN 'true' THEN 'Active'
                WHEN 'false' THEN 'Panding'
            END as status_name,
            count(applicant_individual_modules.is_active) as count"),
        ])
            ->join('applicant_individual_modules', 'applicant_individual.id', '=', 'applicant_individual_modules.applicant_individual_id')
            ->where('applicant_individual_modules.module_id', ModuleEnum::BANKING->value)
            ->groupBy(['applicant_individual_modules.is_active']);

        $applicantCompany = ApplicantCompany::select([
            DB::raw("'".class_basename(ApplicantCompany::class)."' as applicant_type,
            CASE applicant_company_modules.is_active
                WHEN 'true' THEN 'Active'
                WHEN 'false' THEN 'Panding'
            END as status_name,
            count(applicant_company_modules.is_active) as count"),
        ])
            ->join('applicant_company_modules', 'applicant_companies.id', '=', 'applicant_company_modules.applicant_company_id')
            ->where('applicant_company_modules.module_id', ModuleEnum::BANKING->value)
            ->groupBy(['applicant_company_modules.is_active']);

        $statistic = array_merge(
            $applicantIndividual->get()->toArray(),
            $applicantCompany->get()->toArray(),
        );

        $this->postGraphQL(
            [
                'query' => '
                query DashboardUsersStatistic($id: ID) {
                    dashboardUsersStatistic(company_id: $id) {
                        applicant_type
                        status_name
                        count
                    }
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'applicant_type' => (string) $statistic[0]['applicant_type'],
            'status_name' => (string) $statistic[0]['status_name'],
            'count' => $statistic[0]['count'],
        ]);
    }

    public function testQueryDashboardUsersStatisticWithProjectId(): void
    {
        $applicantIndividual = ApplicantIndividual::select([
            DB::raw("'".class_basename(ApplicantIndividual::class)."' as applicant_type,
            CASE applicant_individual_modules.is_active
                WHEN 'true' THEN 'Active'
                WHEN 'false' THEN 'Panding'
            END as status_name,
            count(applicant_individual_modules.is_active) as count"),
        ])
            ->join('applicant_individual_modules', 'applicant_individual.id', '=', 'applicant_individual_modules.applicant_individual_id')
            ->where('applicant_individual_modules.module_id', ModuleEnum::BANKING->value)
            ->groupBy(['applicant_individual_modules.is_active']);

        $applicantCompany = ApplicantCompany::select([
            DB::raw("'".class_basename(ApplicantCompany::class)."' as applicant_type,
            CASE applicant_company_modules.is_active
                WHEN 'true' THEN 'Active'
                WHEN 'false' THEN 'Panding'
            END as status_name,
            count(applicant_company_modules.is_active) as count"),
        ])
            ->join('applicant_company_modules', 'applicant_companies.id', '=', 'applicant_company_modules.applicant_company_id')
            ->where('applicant_company_modules.module_id', ModuleEnum::BANKING->value)
            ->groupBy(['applicant_company_modules.is_active']);

        $statistic = array_merge(
            $applicantIndividual->get()->toArray(),
            $applicantCompany->get()->toArray(),
        );

        $this->postGraphQL(
            [
                'query' => '
                query DashboardUsersStatistic($id: ID) {
                    dashboardUsersStatistic(project_id: $id) {
                        applicant_type
                        status_name
                    }
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'applicant_type' => (string) $statistic[0]['applicant_type'],
            'status_name' => (string) $statistic[0]['status_name'],
        ]);
    }
}
