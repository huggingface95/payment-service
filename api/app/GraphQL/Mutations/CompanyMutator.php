<?php

namespace App\GraphQL\Mutations;

use App\Enums\EmailTemplatesTypeEnum;
use App\Enums\ModuleTagEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividualCompanyPosition;
use App\Models\ApplicantIndividualCompanyRelation;
use App\Models\Company;
use App\Models\EmailTemplate;
use App\Models\Members;
use App\Models\State;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CompanyMutator extends BaseMutator
{
    /**
     * @param    $root
     * @param  array  $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        try {
            DB::beginTransaction();

            $company = Company::create($args);

            $relationsData = [
                'Director',
                'Shareholder',
                'Beneficiary',
            ];

            foreach ($relationsData as $relationData) {
                ApplicantIndividualCompanyRelation::firstOrCreate([
                    'name' => $relationData,
                    'company_id' => $company->id,
                ]);
            }

            $positionsData = [
                'Director',
                'CEO',
                'CFO',
                'CAO',
                'CIO',
                'COO',
            ];

            foreach ($positionsData as $positionData) {
                ApplicantIndividualCompanyPosition::firstOrCreate([
                    'name' => $positionData,
                    'company_id' => $company->id,
                ]);
            }

            $company->paymentProviders()->create(['name' => 'Internal']);
            $company->paymentSystem()->create([
                'name' => 'Internal',
                'payment_provider_id' => $company->paymentProviders()->first()->id,
            ]);

            $templateSubjects = [
                'Sign Up: Email Confirmation',
                'Waiting for approval',
                'Reset Password',
                'KYC: Common Sign Up: Email Confirmation',
                'Account Requisites',
                'Confirm change email',
                'Account suspended',
                'Minimum balance limit has been reached for client',
                'Maximum balance limit has been reached for client',
                'Forgot Password',
                'Waiting for IBAN Generation'
            ];

            foreach ($templateSubjects as $name) {

                $newTemplateData = [
                    'type' => EmailTemplatesTypeEnum::ADMINISTRATION,
                    'service_type' => ModuleTagEnum::BANKING_ADMIN_NOTIFY,
                    'use_layout' => false,
                    'subject' => $name,
                    'content' => "",
                    'member_id' => Auth::user()->id,
                    'company_id' => $company->id,
                    'name' => $name,
                ];

                EmailTemplate::query()->firstOrCreate($newTemplateData);
            }

            DB::commit();

            return $company;
        } catch (\Throwable $exception) {
            DB::rollback();
            throw new GraphqlException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Shared between all fields.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Metadata for advanced query resolution.
     * @return mixed
     */
    public function update($root, array $args, GraphQLContext $context)
    {
        $date = Carbon::now();
        $company = Company::find($args['id']);
        if (isset($args['additional_fields_info'])) {
            $args['additional_fields_info'] = $this->setAdditionalField($args['additional_fields_info']);
        }
        if (isset($args['additional_fields_basic'])) {
            $args['additional_fields_basic'] = $this->setAdditionalField($args['additional_fields_basic']);
        }
        if (isset($args['additional_fields_settings'])) {
            $args['additional_fields_settings'] = $this->setAdditionalField($args['additional_fields_settings']);
        }
        if (isset($args['additional_fields_data'])) {
            $args['additional_fields_data'] = $this->setAdditionalField($args['additional_fields_data']);
        }
        if (isset($args['incorporate_date'])) {
            if (Carbon::parse($args['incorporate_date'])->gt($date)) {
                throw new GraphqlException('incorporate_date cannot be greater than current date and time', 'use');
            }
        }
        $company->update($args);

        return $company;
    }

    public function delete($root, array $args, GraphQLContext $context)
    {
        $company = Company::find($args['id']);

        $company->delete();

        return $company;
    }
}
