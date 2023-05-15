<?php

use App\Enums\ProviderTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddQuoteProviderTypeOnProviderTypeColumnToProjectApiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE project_api_settings DROP CONSTRAINT IF EXISTS project_api_settings_provider_type_check');
        $types = [
            ProviderTypeEnum::PAYMENT->toString(),
            ProviderTypeEnum::IBAN->toString(),
            ProviderTypeEnum::QUOTE->toString(),
        ];
        $result = implode(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));
        DB::statement("ALTER TABLE project_api_settings ADD CONSTRAINT project_api_settings CHECK (provider_type::text = ANY (ARRAY[$result]::text[]))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_api_settings', function (Blueprint $table) {
            //
        });
    }
}
