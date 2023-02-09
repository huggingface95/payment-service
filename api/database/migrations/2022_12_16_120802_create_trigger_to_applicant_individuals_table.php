<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggerToApplicantIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
        CREATE OR REPLACE FUNCTION function_applicant_individual_update_entity()
            RETURNS trigger AS
        $BODY$
        BEGIN
            NEW.entity_id := (SELECT entity_id FROM applicant_individual where company_id = NEW.company_id order by entity_id limit 1);
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
            CREATE OR REPLACE TRIGGER applicant_individual_update_entity
                BEFORE INSERT
                ON applicant_individual
                FOR EACH ROW
            EXECUTE PROCEDURE function_applicant_individual_update_entity();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP TRIGGER applicant_individual_update_entity ON applicant_individual;');
        DB::statement('DROP FUNCTION function_applicant_individual_update_entity;');
    }
}
