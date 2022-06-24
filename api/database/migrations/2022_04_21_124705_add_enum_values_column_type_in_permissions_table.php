<?php

use App\Models\Permissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddEnumValuesColumnTypeInPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE permissions DROP CONSTRAINT IF EXISTS permissions_type_check');
        $types = [
            Permissions::TYPE_ADD, Permissions::TYPE_EDIT, Permissions::TYPE_READ,
            Permissions::TYPE_INFO, Permissions::TYPE_IMPORTANT,
            Permissions::TYPE_REQUIRED, Permissions::TYPE_NO_REQUIRED, Permissions::TYPE_EXPORT,
        ];
        $result = implode(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));
        DB::statement("ALTER TABLE permissions ADD CONSTRAINT permissions CHECK (type::text = ANY (ARRAY[$result]::text[]))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
