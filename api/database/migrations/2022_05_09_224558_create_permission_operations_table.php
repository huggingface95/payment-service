<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_operations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('referer');

            $table->unique(['name', 'referer']);
        });

        Schema::create('permission_operations_parents', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('permission_operations_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permission_operations_id')->references('id')->on('permission_operations')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('permission_operations_binds', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('permission_operations_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permission_operations_id')->references('id')->on('permission_operations')
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
        Schema::dropIfExists('permission_operations');
        Schema::dropIfExists('permission_operations_parents');
        Schema::dropIfExists('permission_operations_binds');
    }
}
