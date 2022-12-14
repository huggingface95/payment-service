<?php

namespace App\GraphQL\Queries;

use App\Models\Clickhouse\ActiveSession;
use Illuminate\Support\Facades\DB;

final class ActiveSessionsQuery
{
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
            ->from((new ActiveSession)->getTable())
            ->where('active', '=',  true)
            ->where('provider', 'individual')
            ->orderBy('created_at', 'DESC');

        if (isset($args['query']) && count($args['query']) > 0) {
            $fields = $args['query'];

            if (isset($fields['created_at'])) {
                $value = substr($fields['created_at'], 0, 10);
                $query->whereBetween('created_at', [$value.' 00:00:00', $value.' 23:59:59']);

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
}
