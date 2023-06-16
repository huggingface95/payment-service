<?php

namespace App\GraphQL\Queries;

use App\Models\ApplicantIndividualLabel;

class ApplicantIndividualLabelsQuery
{
    protected $table = 'applicant_individual_labels';

    public function enabled($_, array $args)
    {
        $first = ApplicantIndividualLabel::query()
            ->select('*')
            ->get();
        $second = ApplicantIndividualLabel::query()
            ->select('*')
            ->where('ailr.applicant_individual_id', $args['applicant_id'])
            ->leftJoin('applicant_individual_label_relation as ailr', 'applicant_individual_labels.id', '=', 'ailr.applicant_individual_label_id')
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
