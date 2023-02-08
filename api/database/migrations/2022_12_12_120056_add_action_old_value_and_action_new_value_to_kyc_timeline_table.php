<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddActionOldValueAndActionNewValueToKycTimelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kyc_timeline', function (Blueprint $table) {
            $table->jsonb('action_old_value')->nullable();
            $table->jsonb('action_new_value')->nullable();
            $table->dropColumn(['action_state']);

            $this->changeEnum(['document_upload', 'document_state', 'verification', 'email', 'profile']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kyc_timeline', function (Blueprint $table) {
            $table->dropColumn('action_old_value');
            $table->dropColumn('action_new_value');
            $table->string('action_state');

            $this->changeEnum(['document_upload', 'document_state', 'verification', 'email']);
        });
    }

    private function changeEnum(array $types): void
    {
        DB::statement('ALTER TABLE kyc_timeline DROP CONSTRAINT kyc_timeline_action_type_check');

        $result = implode(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE kyc_timeline ADD CONSTRAINT kyc_timeline_action_type_check CHECK (action_type::text = ANY (ARRAY[$result]::text[]))");
    }
}
