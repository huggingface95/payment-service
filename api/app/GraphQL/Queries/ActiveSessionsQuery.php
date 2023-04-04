<?php

namespace App\GraphQL\Queries;

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

        if (isset($args['query']) && count($args['query']) > 0) {
            $fields = $args['query'];

            if (isset($fields['created_at'])) {
                $value = substr($fields['created_at'], 0, 10);
                $query->whereBetween('created_at', [$value . ' 00:00:00', $value . ' 23:59:59']);

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
}
