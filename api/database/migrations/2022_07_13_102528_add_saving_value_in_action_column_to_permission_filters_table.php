<?php

use App\Models\PermissionFilter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSavingValueInActionColumnToPermissionFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permission_filters', function (Blueprint $table) {
            DB::statement('ALTER TABLE permission_filters DROP CONSTRAINT permission_filters_action_check');

            $types = PermissionFilter::getEventActions();
            $result = implode(', ', array_map(function ($value) {
                return sprintf("'%s'::character varying", $value);
            }, $types));

            DB::statement("ALTER TABLE permission_filters ADD CONSTRAINT permission_filters_action_check CHECK (action::text = ANY (ARRAY[$result]::text[]))");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission_filters', function (Blueprint $table) {
            //
        });
    }
}
