<?php

namespace App\Models\Scopes;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Traits\ApplicantIdPrefix;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApplicantIndividualCompanyIdScope implements Scope
{
    use ApplicantIdPrefix;

    public function apply(Builder $builder, ApplicantIndividual|ApplicantCompany|Model $model): void
    {
        $operators = implode('|', array_map(function ($v) {
            return "(" . preg_quote($v) . ")";
        }, array_merge($builder->getQuery()->operators, ['in', 'not in'])));

        list($sql, $bindings) = $builder->toRawSql();
        $i = 0;
        $conditions = self::COLUMNS[get_class($builder->getModel())];
        array_reduce($bindings, function ($sql, $b) use (&$i, &$bindings, $conditions, $operators) {
            $newSql = preg_replace_callback('/.*?\?/', function ($matches) use ($i, &$bindings, $conditions, $operators) {
                foreach ($conditions as $check => $columns) {
                    $cs = implode('|', array_map(function ($v) {
                        return "(" . $v . ")";
                    }, array_keys($columns)));
                    if (
                        preg_match("/^{$check}[0-9]+/", (string)$bindings[$i]) &&
                        preg_match("/^.*?{$cs}[ \"]+({$operators})[ (]+\?$/", $matches[0])
                    ) {
                        $bindings[$i] = (int)preg_replace("/^.*?([0-9]+)$/", "$1", $bindings[$i]);
                        break;
                    }
                }
                return substr($matches[0], 0, -1);
            }, $sql, 1);
            $i++;
            return $newSql;
        }, $sql);
        $builder->setBindings($bindings);
    }

}
