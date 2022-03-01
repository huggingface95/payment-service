<?php

namespace App\GraphQL\Queries;

use GraphQL\Exception\InvalidArgument;
use Illuminate\Support\Facades\DB;

class ApplicantCompanyLabelsQuery
{
    protected $table="applicant_company_labels";

    public function enabled($_, array $args)
    {
        $args = DB::table('applicant_company_labels as ail')
            ->select('*',DB::raw('CASE
                WHEN ailr.applicant_company_id is not null and ailr.applicant_company_id = '.$args['company_id'].' THEN true
                WHEN ailr.applicant_company_id = '.$args['company_id'].'  THEN true
                ELSE false
                END AS is_active'))
            ->leftJoin('applicant_company_label_relation as ailr','ail.id','=','ailr.applicant_company_label_id')
            ->get();
        return $args;
    }

}
