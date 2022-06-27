<?php

use App\Models\EmailTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [EmailTemplate::ADMINISTRATION, EmailTemplate::CLIENT])->default(EmailTemplate::ADMINISTRATION);
            $table->enum('service_type', [EmailTemplate::BANKING, EmailTemplate::COMMON, EmailTemplate::SYSTEM])->default(EmailTemplate::BANKING);
            $table->boolean('use_layout')->default(0);
            $table->string('subject');
            $table->longText('content');
            $table->text('header')->nullable();
            $table->text('footer')->nullable();
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
        Schema::dropIfExists('email_templates');
    }
}
