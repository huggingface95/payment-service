<?php

use App\Models\AccountClient;
use App\Models\AccountIndividualCompany;
use App\Models\ApplicantCompany;
use App\Models\ApplicantDocument;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualCompany;
use App\Models\ClientIpAddress;
use App\Models\EmailNotificationClient;
use App\Models\GroupRoleUser;
use App\Models\Members;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeEnumFieldsForApplicantIndividualAndApplicantCompanyAndMembersTables extends Migration
{
    public const INDIVIDUAL = 'ApplicantIndividual';

    public const COMPANY = 'ApplicantCompany';

    public const MEMBER = 'Members';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->changeFieldInTableUp(new AccountClient(), 'client_type');
        $this->changeFieldInTableUp(new AccountIndividualCompany(), 'client_type');
        $this->changeFieldInTableUp(new ApplicantDocument(), 'applicant_type');
        $this->changeFieldInTableUp(new ApplicantIndividualCompany(), 'applicant_type');
        $this->changeFieldInTableUp(new ClientIpAddress(), 'client_type');
        $this->changeFieldInTableUp(new EmailNotificationClient(), 'client_type');
        $this->changeFieldInTableUp(
            new GroupRoleUser(),
            'user_type',
            [ApplicantIndividual::class, ApplicantCompany::class, Members::class, self::INDIVIDUAL, self::COMPANY, self::MEMBER],
            [self::MEMBER, self::INDIVIDUAL, self::COMPANY]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->changeFieldInTableDown(new AccountClient(), 'client_type');
        $this->changeFieldInTableDown(new AccountIndividualCompany(), 'client_type');
        $this->changeFieldInTableDown(new ApplicantDocument(), 'applicant_type');
        $this->changeFieldInTableDown(new ApplicantIndividualCompany(), 'applicant_type');
        $this->changeFieldInTableDown(new ClientIpAddress(), 'client_type');
        $this->changeFieldInTableDown(
            new GroupRoleUser(),
            'user_type',
            [self::MEMBER, self::INDIVIDUAL, self::COMPANY, ApplicantIndividual::class, ApplicantCompany::class, Members::class],
            [Members::class, ApplicantIndividual::class, ApplicantCompany::class]
        );
    }

    private function changeFieldInTableUp($model, $field, $valuesFrom = null, $valuesTo = null): void
    {
        $valuesFrom = $valuesFrom ?? [ApplicantIndividual::class, ApplicantCompany::class, self::INDIVIDUAL, self::COMPANY];
        $valuesTo = $valuesTo ?? [self::INDIVIDUAL, self::COMPANY];

        $this->changeEnum($model->getTable(), $field, $valuesFrom);

        $model->where($field, ApplicantIndividual::class)->update([$field => self::INDIVIDUAL]);
        $model->where($field, ApplicantCompany::class)->update([$field => self::COMPANY]);
        if (in_array(self::MEMBER, $valuesTo)) {
            $model->where($field, Members::class)->update([$field => self::MEMBER]);
        }

        $this->changeEnum($model->getTable(), $field, $valuesTo);
    }

    private function changeFieldInTableDown($model, $field, $valuesFrom = null, $valuesTo = null): void
    {
        $valuesFrom = $valuesFrom ?? [self::INDIVIDUAL, self::COMPANY, ApplicantIndividual::class, ApplicantCompany::class];
        $valuesTo = $valuesTo ?? [ApplicantIndividual::class, ApplicantCompany::class];

        $this->changeEnum($model->getTable(), $field, $valuesFrom);

        $model->where($field, self::INDIVIDUAL)->update([$field => ApplicantIndividual::class]);
        $model->where($field, self::COMPANY)->update([$field => ApplicantCompany::class]);
        if (in_array(Members::class, $valuesTo)) {
            $model->where($field, self::MEMBER)->update([$field => Members::class]);
        }

        $this->changeEnum($model->getTable(), $field, $valuesTo);
    }

    private function changeEnum(string $table, string $column, array $types): void
    {
        DB::statement("ALTER TABLE $table DROP CONSTRAINT ".$table.'_'.$column.'_check');

        $result = implode(', ', array_map(function ($value) {
            return sprintf("'%s'::character varying", $value);
        }, $types));

        DB::statement("ALTER TABLE $table ADD CONSTRAINT ".$table.'_'.$column.'_check CHECK ('.$column."::text = ANY (ARRAY[$result]::text[]))");
        DB::statement("ALTER TABLE $table ALTER COLUMN ".$column." SET DEFAULT '".$types[0]."'");
    }
}
