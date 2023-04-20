<?php

namespace App\GraphQL\Queries;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Clickhouse\ActiveSession;
use App\Models\Members;
use Illuminate\Support\Facades\DB;

final class ActiveSessionsQuery
{
    /**
     * Get data with pagination and filteration
     *
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function get($_, array $args): array
    {
        $query = DB::connection('clickhouse')
            ->query()
            ->from((new ActiveSession())->getTable())
            ->orderBy('created_at', 'DESC');

        $this->filterByQuery($query, $args);

        $result = $query->paginate($args['page'] ?? 1, $args['count'] ?? env('PAGINATE_DEFAULT_COUNT'));

        return [
            'data' => $result->items(),
            'paginatorInfo' => [
                'count' => $result->count(),
                'currentPage' => $result->currentPage(),
                'firstItem' => $result->firstItem(),
                'hasMorePages' => $result->hasMorePages(),
                'lastItem' => $result->lastItem(),
                'lastPage' => $result->lastPage(),
                'perPage' => $result->perPage(),
                'total' => $result->total(),
            ],
        ];
    }

    public function getCompanySessions($_, array $args): ?array
    {
        $query = DB::connection('clickhouse')
            ->query()
            ->from((new ActiveSession())->getTable())
            ->orderBy('created_at', 'DESC');

        if (isset($args['applicant_company_id'])) {
            /** @var ApplicantCompany $applicantCompany */
            $applicantCompany = ApplicantCompany::query()->with('applicantIndividuals')->findOrFail($args['applicant_company_id']);
            $query->whereIn('email', $applicantCompany->applicantIndividuals->pluck('email')->toArray());
        }
        if (isset($args['applicant_individual_id'])) {
            /** @var ApplicantIndividual $individual */
            $individual = ApplicantIndividual::query()->findOrFail($args['applicant_individual_id']);
            $query->where('email', $individual->email);
        }
        if (isset($args['owner_id'])) {
            $individuals = ApplicantIndividual::query()->where('account_manager_member_id', $args['owner_id'])->get();
            $query->whereIn('email', $individuals->pluck('email')->toArray());
        }
        $this->filterByQuery($query, $args);

        $result = $query->paginate($args['page'] ?? 1, $args['count'] ?? env('PAGINATE_DEFAULT_COUNT'));
        return [
            'data' => $result->items(),
            'paginatorInfo' => [
                'count' => $result->count(),
                'currentPage' => $result->currentPage(),
                'firstItem' => $result->firstItem(),
                'hasMorePages' => $result->hasMorePages(),
                'lastItem' => $result->lastItem(),
                'lastPage' => $result->lastPage(),
                'perPage' => $result->perPage(),
                'total' => $result->total(),
            ],
        ];

    }


    public function getMemberActiveSession($_, array $args): ?array
    {
        /** @var Members $member */
        $member = Members::query()->findOrFail($args['member_id']);

        return $member->active_session;
    }

    public function getIndividualActiveSession($_, array $args): ?array
    {
        /** @var ApplicantIndividual $individual */
        $individual = ApplicantIndividual::query()->findOrFail($args['individual_id']);

        return $individual->active_session;
    }

    protected function filterByQuery(object $query, array $args): void
    {
        if (isset($args['query']) && count($args['query']) > 0) {
            $fields = $args['query'];

            if (isset($fields['created_at'])) {
                $value = $fields['created_at'];
                $query->whereBetween('created_at', [substr($value['from'], 0, 10) . ' 00:00:00', substr($value['to'], 0, 10) . ' 23:59:59']);

                unset($fields['created_at']);
            }
            if (count($fields) > 0) {
                $query->where(function ($query) use ($fields) {
                    foreach ($fields as $column => $value) {
                        $query->where($column, $value);
                    }
                });
            }
        }
    }
}
