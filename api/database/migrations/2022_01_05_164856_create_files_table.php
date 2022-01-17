<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name',255); // file name
            $table->string('mime_type',255); // file mime type
            $table->bigInteger('size'); // file size
            $table->string('entity_type',255); // entity_type
            $table->integer('author_id'); //who upload file
            $table->string('storage_path',255); // path to file in ovh
            $table->string('storage_name',255); // name of file in ovh
            $table->string('link',255); // link
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
