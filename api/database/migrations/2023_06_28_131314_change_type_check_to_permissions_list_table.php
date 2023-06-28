<?php

use App\Enums\ClientTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeTypeCheckToPermissionsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE permissions_list DROP CONSTRAINT permissions_list_type_check');


        $result = implode(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, array_map(function ($enum) {
            return $enum->toString();
        }, ClientTypeEnum::cases())));

        DB::statement("ALTER TABLE permissions_list ADD CONSTRAINT permissions_list_type_check CHECK (type::text = ANY (ARRAY[$result]::text[]))");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions_list', function (Blueprint $table) {
            //
        });
    }
}
