<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCompanyPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('is_all_companies');
        });

        Schema::create('permission_category_role', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_category_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('permission_category_id')->references('id')->on('permission_category')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('is_all_companies')->default(false);
        });
        Schema::dropIfExists('permission_category_role');
    }
}
