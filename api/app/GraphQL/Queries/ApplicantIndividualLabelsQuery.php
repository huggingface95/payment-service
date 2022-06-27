<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\DB;

class ApplicantIndividualLabelsQuery
{
    protected $table = 'applicant_individual_labels';

    public function enabled($_, array $args)
    {
        $first = DB::table('applicant_individual_labels as ail')
            ->select('*')
            ->get();
        $second = DB::table('applicant_individual_labels as ail')
            ->select('*')
            ->where('ailr.applicant_individual_id', $args['applicant_id'])
            ->leftJoin('applicant_individual_label_relation as ailr', 'ail.id', '=', 'ailr.applicant_individual_label_id')
            ->get();
        $merge = $first->merge($second);
        $unique = $merge->unique('name');
        $diff = $unique->whereNotIn('id', $second->pluck('id'))->toArray();
        $diff2 = $unique->whereIn('id', $second->pluck('id'))->toArray();
        $values = array_values($diff);
        $arr1 = json_decode(json_encode($values), true);
        $arr2 = json_decode(json_encode($diff2), true);
        foreach ($arr2 as &$value) {
            $value += ['is_active' => true];
        }

        foreach ($arr1 as &$value) {
            $value += ['is_active' => false];
        }

        $result = array_merge($arr1, $arr2);

        return $result;
    }
}
