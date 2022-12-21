<?php

use App\Enums\KycTimelineActionTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeActionTypeToEnumListFromEnumsInKycTimelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kyc_timeline', function (Blueprint $table) {
            $cases = KycTimelineActionTypeEnum::cases();
            foreach ($cases as $enum) {
                $listEnums[] = $enum->value;
            }

            $this->changeEnum($listEnums);
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
            $this->changeEnum(['document_upload', 'document_state', 'verification', 'email', 'profile']);
        });
    }

    private function changeEnum(array $types): void
    {
        DB::statement("ALTER TABLE kyc_timeline DROP CONSTRAINT kyc_timeline_action_type_check");

        $result = join(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE kyc_timeline ADD CONSTRAINT kyc_timeline_action_type_check CHECK (action_type::text = ANY (ARRAY[$result]::text[]))");
    }
}
