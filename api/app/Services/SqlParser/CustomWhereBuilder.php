<?php

namespace App\Services\SqlParser;

use PHPSQLParser\builders\WhereBuilder;

class CustomWhereBuilder extends WhereBuilder
{
    public function overwriteBindings(array &$bindings, array $parsed, string $table, string $prefix): void
    {
        foreach ($bindings as &$binding) {
            if (
                preg_match("/^{$prefix}[0-9]+/", (string)$binding)
            ) {
                $sql = "";
                foreach ($parsed as $j => $v) {

                    $this->customBuild($v, $sql);
                    if (preg_match("/(\"{$table}\".\"id\")|(\"id\")/", $sql)) {
                        $sql = "";
                        foreach (is_array($v['sub_tree']) ? $v['sub_tree'] : array_slice($parsed, $j + 1) as $sV) {
                            $this->customBuild($sV, $sql);
                            if (str_contains($sql, $binding)) {
                                $binding = (int)str_replace("{$prefix}", "", $binding);
                                break;
                            }
                        }
                        break;
                    }
                    $sql .= " ";
                }
            }
        }
    }


    private function customBuild($v, &$sql): void
    {
        $sql .= $this->buildOperator($v);
        $sql .= $this->buildConstant($v);
        $sql .= $this->buildColRef($v);
        $sql .= $this->buildSubQuery($v);
        $sql .= $this->buildInList($v);
        $sql .= $this->buildFunction($v);
        $sql .= $this->buildWhereExpression($v);
        $sql .= $this->buildWhereBracketExpression($v);
        $sql .= $this->buildUserVariable($v);
        $sql .= $this->buildReserved($v);
    }

}
