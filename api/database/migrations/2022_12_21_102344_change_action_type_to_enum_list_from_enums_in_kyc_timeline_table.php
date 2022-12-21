<?php

use App\Enums\KycTimelineActionTypeEnum;
use App\Enums\ModuleEnum;
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
            $casesActionTypeEnum = KycTimelineActionTypeEnum::cases();
            foreach ($casesActionTypeEnum as $enum) {
                $listActionTypeEnums[] = $enum->value;
            }

            $this->changeEnum('action_type', $listActionTypeEnums);
            $table->dropColumn('tag');
        });

        Schema::table('kyc_timeline', function (Blueprint $table) {
            $casesModuleEnum = ModuleEnum::cases();
            foreach ($casesModuleEnum as $enum) {
                $listModuleEnums[] = $enum->toString();
            }

            $table->enum('tag', $listModuleEnums)->default(ModuleEnum::KYC->toString());
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
            $this->changeEnum('action_type', ['document_upload', 'document_state', 'verification', 'email', 'profile']);
            $table->dropColumn('tag');
        });

        Schema::table('kyc_timeline', function (Blueprint $table) {
            $table->string('tag')->default(ModuleEnum::KYC->toString());
        });
    }

    private function changeEnum(string $field, array $types): void
    {
        DB::statement("ALTER TABLE kyc_timeline DROP CONSTRAINT kyc_timeline_" . $field . "_check");

        $result = join(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE kyc_timeline ADD CONSTRAINT kyc_timeline_" . $field . "_check CHECK (" . $field . "::text = ANY (ARRAY[$result]::text[]))");
    }
}
