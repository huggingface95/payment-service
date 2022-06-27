<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApplicantFilterByMemberScope implements Scope
{
    protected ?array $ids;

    public function __construct(?array $ids)
    {
        $this->ids = $ids;
    }

    public function apply(Builder $builder, Model $model)
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $log) {
            if (isset($log['function']) && $log['function'] == 'getApplicantIdsByAuthMember') {
                return;
            }
        }

        if (preg_match('/^(SELECT|select)/', $builder->getQuery()->toSql())) {
            if ($this->ids && preg_match("/applicant_individual(?=[\.\"\' ])|applicant_companies(?=[\.\"\' ])/", $builder->getQuery()->toSql(), $matches)) {
                foreach ($matches as $match) {
                    /**  applicant_inidividual|applicant_companies $match */
                    $builder->whereIn("{$match}.id", $this->ids[$match]);
                }
            }
        }
    }
}
