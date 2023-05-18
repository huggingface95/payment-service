<?php

use Illuminate\Support\Facades\DB;

if (! function_exists('changeEnumField')) {
    function changeEnumField(string $table, string $column, array $types, bool $nullable = false): void
    {
        DB::statement("ALTER TABLE $table DROP CONSTRAINT ".$table.'_'.$column.'_check');

        $result = implode(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        $default = $nullable ? 'NULL' : "'".$types[0]."'";

        DB::statement("ALTER TABLE $table ADD CONSTRAINT ".$table.'_'.$column.'_check CHECK ('.$column."::text = ANY (ARRAY[$result]::text[]))");
        DB::statement("ALTER TABLE $table ALTER COLUMN ".$column.' SET DEFAULT '.$default);
    }
}
