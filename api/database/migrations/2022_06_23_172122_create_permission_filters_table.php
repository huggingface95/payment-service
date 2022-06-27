<?php

use App\Models\PermissionFilter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_filters', function (Blueprint $table) {
            $table->id();
            $table->enum('mode', PermissionFilter::getModes())->default(PermissionFilter::SCOPE_MODE);
            $table->enum('action', PermissionFilter::getEventActions())->nullable();
            $table->string('table');
            $table->string('column');
            $table->string('value');
        });

        Schema::create('permission_filters_binds', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('permission_filters_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permission_filters_id')->references('id')->on('permission_filters')
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
        Schema::dropIfExists('permission_queries');
    }
}
