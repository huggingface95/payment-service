<?php

namespace App\GraphQL\Queries;

use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\GetMemberOrIndividualClickhouseTrait;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Clickhouse\AuthenticationLog;
use App\Models\Members;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class AuthenticationLogsQuery
{
    use GetMemberOrIndividualClickhouseTrait;

    /**
     * Get data with pagination and filteration
     *
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args)
    {
        $query = DB::connection('clickhouse')
            ->query()
            ->from((new AuthenticationLog())->getTable());

        $this->filterByQueryAndSort($query, $args);

        $result = $query->paginate($args['page'] ?? 1, $args['first'] ?? env('PAGINATE_DEFAULT_COUNT'));

        $this->addClient($result);

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

    /**
     * @throws GraphqlException
     */
    public function getMember($_, array $args): array
    {
        $query = DB::connection('clickhouse')
            ->query()
            ->from((new AuthenticationLog())->getTable())
            ->where('provider', 'member');

        if (isset($args['member_id'])) {
            $member = Members::find($args['member_id']);
            if (! $member) {
                throw new GraphqlException('Not found Member', 'not found', 404);
            }
            $query->where('email', $member->email);
        }

        $this->filterByQueryAndSort($query, $args);

        $result = $query->paginate($args['page'] ?? 1, $args['first'] ?? env('PAGINATE_DEFAULT_COUNT'));

        $this->addClient($result);

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

    /**
     * @throws GraphqlException
     */
    public function getIndividual($_, array $args): array
    {
        $query = DB::connection('clickhouse')
            ->query()
            ->from((new AuthenticationLog())->getTable())
            ->where('provider', 'applicant');

        if (isset($args['individual_id'])) {
            /** @var ApplicantIndividual $individual */
            $individual = ApplicantIndividual::query()->find($args['individual_id']);
            if (! $individual) {
                throw new GraphqlException('Not found ApplicantIndividual', 'not found', 404);
            }
            $query->where('email', $individual->email);
        }

        $this->filterByQueryAndSort($query, $args);

        $result = $query->paginate($args['page'] ?? 1, $args['first'] ?? env('PAGINATE_DEFAULT_COUNT'));

        $this->addClient($result);

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

    /**
     * @throws GraphqlException
     */
    public function getCompany($_, array $args): array
    {
        $query = DB::connection('clickhouse')
            ->query()
            ->from((new AuthenticationLog())->getTable())
            ->where('provider', 'applicant');

        if (isset($args['applicant_company_id'])) {
            /** @var ApplicantCompany $applicantCompany */
            $applicantCompany = ApplicantCompany::query()->with('applicantIndividuals')->find($args['applicant_company_id']);
            if (! $applicantCompany) {
                throw new GraphqlException('Not found ApplicantCompany', 'not found', 404);
            }
            $query->whereIn('email', $applicantCompany->applicantIndividuals->pluck('email')->toArray());
        }

        if (isset($args['applicant_individual_id'])) {
            /** @var ApplicantIndividual $individual */
            $individual = ApplicantIndividual::query()->find($args['applicant_individual_id']);
            if (! $individual) {
                throw new GraphqlException('Not found ApplicantIndividual', 'not found', 404);
            }
            $query->where('email', $individual->email);
        }

        if (isset($args['owner_id'])) {
            $individuals = ApplicantIndividual::query()->where('account_manager_member_id', $args['owner_id'])->get();
            $query->whereIn('email', $individuals->pluck('email')->toArray());
        }

        $this->filterByQueryAndSort($query, $args);

        $result = $query->paginate($args['page'] ?? 1, $args['first'] ?? env('PAGINATE_DEFAULT_COUNT'));

        $this->addClient($result);

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

    protected function filterByQueryAndSort(object $query, array $args): void
    {
        if (isset($args['query']) && count($args['query']) > 0) {
            $fields = $args['query'];

            if (isset($fields['expired_at'])) {
                $value = $fields['expired_at'];
                $query->whereBetween('expired_at', [substr($value['from'], 0, 10).' 00:00:00', substr($value['to'], 0, 10).' 23:59:59']);

                unset($fields['expired_at']);
            }

            if (isset($fields['created_at'])) {
                $value = $fields['created_at'];
                $query->whereBetween('created_at', [substr($value['from'], 0, 10).' 00:00:00', substr($value['to'], 0, 10).' 23:59:59']);

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

        if (isset($args['orderBy']) && count($args['orderBy']) > 0) {
            $fields = $args['orderBy'];

            foreach ($fields as $field) {
                $query->orderBy(Str::lower($field['column']), $field['order']);
            }
        } else {
            $query->orderBy('id', 'DESC');
        }
    }
}
