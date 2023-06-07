<?php

namespace App\Services\SqlParser;

use PHPSQLParser\PHPSQLParser;

class SqlParserService
{

    protected CustomWhereBuilder $whereBuilder;

    public function __construct()
    {
        $this->whereBuilder = new CustomWhereBuilder();
    }


    public function parseAndOverwriteBindings(array $bindings, string $sql, string $table, string $prefix): array
    {
        $parser = new PHPSQLParser($sql);
        if (isset($parser->parsed['WHERE'])) {
            $this->whereBuilder->overwriteBindings($bindings, $parser->parsed['WHERE'], $table, $prefix);
        }

        return $bindings;
    }
}
