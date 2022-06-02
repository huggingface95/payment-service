<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApplicantFilterByMemberScope implements Scope
{
    protected array $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function apply(Builder $builder, Model $model)
    {
        //TODO Replace when guards are ready  Ex. Auth::user('members')
        if (preg_match("/applicant_individual|applicant_companies/", $builder->getQuery()->toSql(), $matches)) {
            foreach ($matches as $match) {
                $builder->whereIn("{$match}.id", $this->ids);
            }
        }
    }
}
