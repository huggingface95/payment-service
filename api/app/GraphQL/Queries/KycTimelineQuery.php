<?php

namespace App\GraphQL\Queries;

use App\Models\KycTimeline;
use Illuminate\Support\Str;

class KycTimelineQuery
{
    public function get($_, array $args): array
    {
        $kycTimelines = KycTimeline::query()
            ->where('applicant_id', $args['applicant_id'])
            ->where('applicant_type', $args['applicant_type'])
            ->where('company_id', $args['company_id']);

        if (isset($args['orderBy']) && count($args['orderBy']) > 0) {
            $fields = $args['orderBy'];

            foreach ($fields as $field) {
                $kycTimelines->orderBy(Str::lower($field['column']), $field['order']);
            }
        } else {
            $kycTimelines->orderBy('id', 'DESC');
        }

        $result = $kycTimelines->paginate($args['first'] ?? env('PAGINATE_DEFAULT_COUNT'), ['*'], 'page', $args['page'] ?? 1);

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
