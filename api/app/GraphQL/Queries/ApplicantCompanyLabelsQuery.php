<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ApplicantCompanyLabelsQuery
{
    protected $table="applicant_company_labels";

    public function enabled($_, array $args): Collection
    {
        return DB::table('applicant_company_labels as l')
            ->select('l.*', 'applicant_company_id AS company_id',DB::raw('r.applicant_company_id = 8  AS is_active'))
            ->leftJoin('applicant_company_label_relation as r','l.id','=','r.applicant_company_label_id')
            ->get();
    }

}
