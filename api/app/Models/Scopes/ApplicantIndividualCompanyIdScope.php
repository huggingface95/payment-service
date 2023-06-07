<?php

namespace App\Models\Scopes;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Services\SqlParser\SqlParserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApplicantIndividualCompanyIdScope implements Scope
{
    protected SqlParserService $service;

    public function __construct()
    {
        $this->service = new SqlParserService();
    }

    public function apply(Builder $builder, ApplicantIndividual|ApplicantCompany|Model $model): void
    {
        list($sql, $bindings) = $builder->toRawSql();
        $bindings = $this->service->parseAndOverwriteBindings($bindings, $sql, $model->getTable(), $model->getPrefixName());
        $builder->setBindings($bindings);
    }

}
