<?php

namespace App\Models\Scopes;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use PHPSQLParser\PHPSQLParser;

class ApplicantIndividualCompanyIdScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        list($sql, $bindings) = $builder->toRawSql();

        $parser = new PHPSQLParser($sql);
        if (isset($parser->parsed['WHERE'])){
            $this->recursiveOverwriteBindings($parser->parsed['WHERE'], $bindings);
            $builder->setBindings($bindings);
        }

    }


    private function recursiveOverwriteBindings(array $wheres, array &$bindings, int $i = -1): void
    {
        if (is_array($wheres[0]['sub_tree'])) {
            $this->recursiveOverwriteBindings($wheres[0]['sub_tree'], $bindings, $i);
        } else if (count($wheres) % 3 == 0 || count($wheres) % 4 == 0) {
            $chunk = 4;
            $value = 3;
            if ($wheres[2]['base_expr'] != 'operator') {
                $chunk = 3;
                $value = 2;
            }

            foreach (array_chunk($wheres, $chunk) as $where) {
                $i++;
                if (in_array($where[0]['base_expr'], ['"applicant_individual"."id"', '"applicant_companies"."id"', '"id"'])) {
                    if (is_string($where[$value]['base_expr']) && preg_match(sprintf("/(%s)|(%s)/", ApplicantIndividual::ID_PREFIX, ApplicantCompany::ID_PREFIX), $where[$value]['base_expr'])) {
                        preg_replace_callback(sprintf('/(%s[0-9]+)|(%s[0-9]+)/', ApplicantIndividual::ID_PREFIX, ApplicantCompany::ID_PREFIX), function ($m) use (&$bindings, &$i) {
                            $bindings[$i] = (int)preg_replace(sprintf('/(%s)|(%s)/', ApplicantIndividual::ID_PREFIX, ApplicantCompany::ID_PREFIX), '', $m[1]);
                            $i++;
                        }, $where[$value]['base_expr']);
                    }
                }
            }
        }
    }

}
