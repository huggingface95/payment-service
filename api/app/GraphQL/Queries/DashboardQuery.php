<?php

namespace App\GraphQL\Queries;

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

class DashboardQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function accountsStatistic($_, array $args)
    {
        $statistic = Account::select([
            'account_states.name as account_state_name', 'payment_provider.name as payment_provider_name',
            'payment_provider_id', 'account_state_id', DB::raw('count(account_state_id) as count'),
        ])
            ->join('account_states', 'accounts.account_state_id', '=', 'account_states.id')
            ->join('payment_provider', 'accounts.payment_provider_id', '=', 'payment_provider.id')
            ->groupBy(['payment_provider.name', 'payment_provider_id', 'account_state_id', 'account_states.name']);

        if (isset($args['created_at']['from']) && isset($args['created_at']['to'])) {
            $statistic->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
        }

        if (isset($args['payment_provider_id'])) {
            $statistic->where('payment_provider_id', $args['payment_provider_id']);
        }

        return $statistic->get();
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function transfersStatistic($_, array $args)
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

        if (isset($args['created_at']['from']) && isset($args['created_at']['to'])) {
            $transferIncomingStatistic->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
            $transferOutgoingStatistic->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
            $transferBetweenUserStatistic->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
            $transferBetweenAccountStatistic->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
        }

        if (isset($args['company_id'])) {
            $transferIncomingStatistic->where('company_id', $args['company_id']);
            $transferOutgoingStatistic->where('company_id', $args['company_id']);
            $transferBetweenUserStatistic->where('company_id', $args['company_id']);
            $transferBetweenAccountStatistic->where('company_id', $args['company_id']);
        }

        if (isset($args['payment_bank_id'])) {
            $transferIncomingStatistic->where('payment_bank_id', $args['payment_bank_id']);
            $transferOutgoingStatistic->where('payment_bank_id', $args['payment_bank_id']);
            $transferBetweenUserStatistic->where('payment_bank_id', $args['payment_bank_id']);
            $transferBetweenAccountStatistic->where('payment_bank_id', $args['payment_bank_id']);
        }

        if (isset($args['payment_provider_id'])) {
            $transferIncomingStatistic->where('payment_provider_id', $args['payment_provider_id']);
            $transferOutgoingStatistic->where('payment_provider_id', $args['payment_provider_id']);
            $transferBetweenUserStatistic->where('payment_provider_id', $args['payment_provider_id']);
            $transferBetweenAccountStatistic->where('payment_provider_id', $args['payment_provider_id']);
        }

        if (isset($args['payment_system_id'])) {
            $transferIncomingStatistic->where('payment_system_id', $args['payment_system_id']);
            $transferOutgoingStatistic->where('payment_system_id', $args['payment_system_id']);
            $transferBetweenUserStatistic->where('payment_system_id', $args['payment_system_id']);
            $transferBetweenAccountStatistic->where('payment_system_id', $args['payment_system_id']);
        }

        return array_merge(
            $transferIncomingStatistic->get()->toArray(),
            $transferOutgoingStatistic->get()->toArray(),
            $transferBetweenUserStatistic->get()->toArray(),
            $transferBetweenAccountStatistic->get()->toArray(),
        );
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function ticketsStatistic($_, array $args)
    {
        $statistic = Ticket::select(['ticket_statuses.name as status_name', 'status as status_id', DB::raw('count(status) as count')])
            ->join('ticket_statuses', 'tickets.status', '=', 'ticket_statuses.id')
            ->groupBy(['status', 'ticket_statuses.name']);

        if (isset($args['created_at']['from']) && isset($args['created_at']['to'])) {
            $statistic->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
        }

        return $statistic->get();
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function usersStatistic($_, array $args)
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

        if (isset($args['company_id'])) {
            $applicantIndividual->where('company_id', $args['company_id']);
            $applicantCompany->where('company_id', $args['company_id']);
        }

        if (isset($args['created_at']['from']) && isset($args['created_at']['to'])) {
            $applicantIndividual->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
            $applicantCompany->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
        }

        if (isset($args['project_id'])) {
            $applicantIndividual->where('project_id', $args['project_id']);
            $applicantCompany->where('project_id', $args['project_id']);
        }

        return array_merge(
            $applicantIndividual->get()->toArray(),
            $applicantCompany->get()->toArray(),
        );
    }
}
