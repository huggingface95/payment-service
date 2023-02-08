<?php

namespace App\Models\Scopes;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApplicantFilterByMemberScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (BaseModel::$applicantIds && preg_match('/^(SELECT|select)/', $builder->getQuery()->toSql())) {
            if (preg_match("/applicant_individual(?=[\.\"\' ])|applicant_companies(?=[\.\"\' ])|members(?=[\.\"\' ])/", $builder->getQuery()->toSql(), $matches)) {
                foreach ($matches as $match) {
                    if (count(BaseModel::$applicantIds[$match])) {
                        /**  applicant_inidividual|applicant_companies|members $match */
                        $builder->whereIn("{$match}.id", BaseModel::$applicantIds[$match]);
                    }
                }
            }
        }
    }
}
