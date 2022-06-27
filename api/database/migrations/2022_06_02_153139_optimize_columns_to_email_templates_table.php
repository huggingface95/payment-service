<?php

use App\Models\EmailTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeColumnsToEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE email_templates DROP CONSTRAINT email_templates_service_type_check');

        $types = EmailTemplate::getServiceTypes();
        $result = implode(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE email_templates ADD CONSTRAINT email_templates_service_type_check CHECK (service_type::text = ANY (ARRAY[$result]::text[]))");

        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('header')->nullable()->change();
            $table->string('footer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            //
        });
    }
}
