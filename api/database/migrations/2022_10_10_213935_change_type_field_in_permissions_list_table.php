<?php

use App\Models\PermissionsList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeTypeFieldInPermissionsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->changeEnum(['member', 'individual', 'applicant']);

        PermissionsList::where('type', 'individual')->update(['type' => 'applicant']);

        $this->changeEnum(['member', 'applicant']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->changeEnum(['member', 'individual', 'applicant']);

        PermissionsList::where('type', 'applicant')->update(['type' => 'individual']);

        $this->changeEnum(['member', 'individual']);
    }

    private function changeEnum(array $types): void
    {
        DB::statement("ALTER TABLE permissions_list DROP CONSTRAINT permissions_list_type_check");

        $result = join(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE permissions_list ADD CONSTRAINT permissions_list_type_check CHECK (type::text = ANY (ARRAY[$result]::text[]))");
    }
}
