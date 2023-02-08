<?php

use App\Enums\FileEntityTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeEntityTypeColumnToFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $enums = implode(', ', array_map(function ($enum) {
            return sprintf("'%s'::character varying", $enum->toString());
        }, FileEntityTypeEnum::cases()));

        DB::statement("ALTER TABLE files ADD CONSTRAINT entity_type_check CHECK (entity_type::text = ANY (ARRAY[{$enums}]::text[]))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE files DROP CONSTRAINT entity_type_check');
    }
}
