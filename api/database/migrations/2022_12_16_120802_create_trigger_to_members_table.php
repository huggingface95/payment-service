<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggerToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
        CREATE OR REPLACE FUNCTION function_member_update_entity()
            RETURNS trigger AS
        $BODY$
        BEGIN
            NEW.entity_id := (SELECT entity_id FROM members where company_id = NEW.company_id order by entity_id limit 1);
            if NEW.entity_id is null then
                NEW.entity_id = 1;
            else
                NEW.entity_id = NEW.entity_id + 1;
            END if;

        --     using NEW
        --     NEW.entity_id = entity

            RETURN NEW;
        END;
        $BODY$
            LANGUAGE plpgsql;
        ');
        DB::statement('
            CREATE OR REPLACE TRIGGER member_update_entity
                BEFORE INSERT
                ON members
                FOR EACH ROW
            EXECUTE PROCEDURE function_member_update_entity();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP TRIGGER member_update_entity ON members;');
        DB::statement('DROP FUNCTION function_member_update_entity;');
    }
}
