<?php

namespace App\GraphQL\Queries;

use GraphQL\Exception\InvalidArgument;
use Illuminate\Support\Facades\DB;

class ApplicantIndividualLabelsQuery
{
    protected $table="applicant_individual_labels";

    public function enabled($_, array $args)
    {
        $args = DB::table('applicant_individual_labels as ail')
            ->select('*',DB::raw('CASE
                WHEN ailr.applicant_individual_id is not null THEN true
                ELSE false
                END AS is_active'))
            ->leftJoin('applicant_individual_label_relation as ailr','ail.id','=','ailr.applicant_individual_label_id')
            ->where('ailr.applicant_individual_id','=',$args['applicant_id'])
            ->orWhereNull('ailr.applicant_individual_id')->orderBy('ail.id')
            ->get();
        return $args;
    }

}
