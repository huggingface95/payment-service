<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueToPermissionOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permission_operations', function (Blueprint $table) {
            $table->dropUnique('permission_operations_name_referer_unique');
            $table->unique(['referer', 'type',  'name', 'method']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission_operations', function (Blueprint $table) {
            $table->dropUnique('permission_operations_referer_type_name_method_unique');
            $table->unique(['name', 'referer']);
        });
    }
}
