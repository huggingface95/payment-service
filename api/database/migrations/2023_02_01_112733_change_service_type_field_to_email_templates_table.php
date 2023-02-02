<?php

use App\Enums\ModuleTagEnum;
use App\Models\EmailTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeServiceTypeFieldToEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $moduleTagEnum = ModuleTagEnum::cases();
            foreach ($moduleTagEnum as $enum) {
                $newEnums[] = $enum->value;
            }
            $oldEnums = [EmailTemplate::BANKING, EmailTemplate::COMMON, EmailTemplate::SYSTEM, EmailTemplate::ADMIN];
            $enums = array_merge($oldEnums, $newEnums);

            $this->changeEnum('service_type', $enums);
        });

        $bankingSystemSubjects = [
            'Waiting for Approval ({account_number})',
            'Waiting for Account# Generation {id}',
            'Awaiting IBAN ({account_id})',
            'Account Status ({account_number}): Active',
            'Account Status ({account_number}): Closed',
            'Account Status ({account_number}): Suspended',
            'Account Status ({id}): Rejected',
            'Account Requisites',
        ];

        $bankingCommonSubjects = [
            'Welcome! Confirm your email address',
            'Registration Details',
            'Password Recovery',
            'Successful Password Reset',
            'Did You Login From a New Device?',
            'Confirm your IP Address',
            'You have added a new Trusted device',
            'You have removed a Trusted device',
        ];

        foreach ($bankingSystemSubjects as $subject) {
            EmailTemplate::whereRaw("lower(subject) LIKE '%" . strtolower($subject) . "%'")
                ->where('service_type', EmailTemplate::BANKING)
                ->update([
                    'service_type' => ModuleTagEnum::BANKING_SYSTEM->value,
                ]);
        }

        foreach ($bankingCommonSubjects as $subject) {
            EmailTemplate::whereRaw("lower(subject) LIKE '%" . strtolower($subject) . "%'")
                ->where('service_type', EmailTemplate::COMMON)
                ->update([
                    'service_type' => ModuleTagEnum::BANKING_COMMON->value,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $bankingSystemSubjects = [
            'Waiting for Approval ({account_number})',
            'Waiting for Account# Generation {id}',
            'Awaiting IBAN ({account_id})',
            'Account Status ({account_number}): Active',
            'Account Status ({account_number}): Closed',
            'Account Status ({account_number}): Suspended',
            'Account Status ({id}): Rejected',
            'Account Requisites',
        ];

        $bankingCommonSubjects = [
            'Welcome! Confirm your email address',
            'Registration Details',
            'Password Recovery',
            'Successful Password Reset',
            'Did You Login From a New Device?',
            'Confirm your IP Address',
            'You have added a new Trusted device',
            'You have removed a Trusted device',
        ];

        foreach ($bankingSystemSubjects as $subject) {
            EmailTemplate::whereRaw("lower(subject) LIKE '%" . strtolower($subject) . "%'")
                ->where('service_type', ModuleTagEnum::BANKING_SYSTEM->value)
                ->update([
                    'service_type' => EmailTemplate::BANKING,
                ]);
        }

        foreach ($bankingCommonSubjects as $subject) {
            EmailTemplate::whereRaw("lower(subject) LIKE '%" . strtolower($subject) . "%'")
                ->where('service_type', ModuleTagEnum::BANKING_COMMON->value)
                ->update([
                    'service_type' => EmailTemplate::COMMON,
                ]);
        }

        Schema::table('email_templates', function (Blueprint $table) {
            $enums = [EmailTemplate::BANKING, EmailTemplate::COMMON, EmailTemplate::SYSTEM, EmailTemplate::ADMIN];

            $this->changeEnum('service_type', $enums);
        });
    }

    private function changeEnum(string $field, array $types): void
    {
        DB::statement("ALTER TABLE email_templates DROP CONSTRAINT email_templates_" . $field . "_check");

        $result = join(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE email_templates ADD CONSTRAINT email_templates_" . $field . "_check CHECK (" . $field . "::text = ANY (ARRAY[$result]::text[]))");
    }
}
