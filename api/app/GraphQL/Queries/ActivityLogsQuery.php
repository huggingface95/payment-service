<?php

namespace App\GraphQL\Queries;

use App\Models\Clickhouse\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class ActivityLogsQuery
{

    /**
     * Get data with pagination and filteration
     *
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args): array
    {
        $query = DB::connection('clickhouse')
            ->query()
            ->from((new ActivityLog)->getTable());

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

        if (isset($args['orderBy']) && count($args['orderBy']) > 0) {
            $fields = $args['orderBy'];

            foreach ($fields as $field) {
                $query->orderBy(Str::lower($field['column']), $field['order']);
            }
        } else {
            $query->orderBy('id', 'DESC');
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
