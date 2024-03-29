<?php

namespace App\Services;

use App\Enums\ApplicantRiskLevelEnum;
use App\Enums\ApplicantStateEnum;
use App\Enums\ApplicantStateReasonEnum;
use App\Enums\ApplicantStatusEnum;
use App\Enums\DocumentStateEnum;
use App\Enums\KycTimelineActionTypeEnum;
use App\Enums\ModuleEnum;
use App\Models\ApplicantCompany;
use App\Models\ApplicantCompanyNotes;
use App\Models\ApplicantDocument;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualNotes;
use App\Models\KycTimeline;
use App\Models\Members;
use Carbon\Carbon;
use Jenssegers\Agent\Facades\Agent;

class KycTimelineService extends AbstractService
{
    protected $fileds = [];

    public function __construct()
    {
        $this->fileds = [
            'os' => Agent::platform() ? Agent::platform() : 'unknown',
            'browser' => Agent::browser() ? Agent::browser() : 'unknown',
            'ip' => $this->getIp(),
            'created_at' => Carbon::now(),
            'tag' => ModuleEnum::KYC->toString(),
            'applicant_type' => class_basename(ApplicantIndividual::class),
        ];
    }

    private function createRow(array $data): void
    {
        KycTimeline::create(array_merge(
            $this->fileds,
            $data,
        ));
    }

    public function logApplicantIndividual(ApplicantIndividual $applicantIndividual, Members|null $member): void
    {
        $newValues = $this->getChanges($applicantIndividual);
        if (empty($newValues)) {
            return;
        }

        if (array_key_exists('applicant_status_id', $newValues)) {
            $this->logApplicantIndividualStatus($applicantIndividual, $member);

            return;
        }

        $oldValues = [];
        foreach ($newValues as $field => $newValue) {
            $oldValues[$field] = $applicantIndividual->getOriginal($field);
        }

        $this->createRow([
            'creator_id' => $member->id ?? null,
            'action' => 'Profile updated',
            'action_type' => KycTimelineActionTypeEnum::PROFILE->value,
            'action_old_value' => $this->replaceWithValues($oldValues),
            'action_new_value' => $this->replaceWithValues($newValues),
            'company_id' => $applicantIndividual->company_id,
            'applicant_id' => $applicantIndividual->getOriginal('id'),
        ]);
    }

    public function logApplicantIndividualStatus(ApplicantIndividual $applicantIndividual, Members|null $member): void
    {
        $newValue = ['applicant_status_id' => $applicantIndividual->getChanges()['applicant_status_id']];
        $oldValue = ['applicant_status_id' => $applicantIndividual->getOriginal('applicant_status_id')];

        $this->createRow([
            'creator_id' => $member->id ?? null,
            'action' => 'Status changed',
            'action_type' => KycTimelineActionTypeEnum::VERIFICATION->value,
            'action_old_value' => $this->replaceWithValues($oldValue),
            'action_new_value' => $this->replaceWithValues($newValue),
            'company_id' => $applicantIndividual->company_id,
            'applicant_id' => $applicantIndividual->getOriginal('id'),
        ]);
    }

    public function logApplicantIndividualNote(ApplicantIndividualNotes $applicantIndividualNote, Members|null $member): void
    {
        $applicant = $applicantIndividualNote->applicant;

        $this->createRow([
            'creator_id' => $member->id ?? null,
            'action' => 'Comment added',
            'action_type' => KycTimelineActionTypeEnum::PROFILE->value,
            'company_id' => $applicant->company_id,
            'applicant_id' => $applicantIndividualNote->applicant_individual_id,
        ]);
    }

    public function logApplicantDocumentCreated(ApplicantDocument $document, Members|null $member): void
    {
        $this->createRow([
            'creator_id' => $member->id ?? null,
            'action' => 'Document uploaded',
            'action_type' => KycTimelineActionTypeEnum::DOCUMENT_UPLOAD->value,
            'document_id' => $document->id,
            'company_id' => $document->company_id,
            'applicant_id' => $document->applicant_id,
            'applicant_type' => $document->applicant_type,
        ]);
    }

    public function logApplicantDocumentUpdated(ApplicantDocument $document, Members|null $member): void
    {
        $newValues = $document->getChanges();
        unset($newValues['updated_at']);

        $oldValues = [];
        foreach ($newValues as $field => $newValue) {
            $oldValues[$field] = $document->getOriginal($field);
        }

        $this->createRow([
            'creator_id' => $member->id ?? null,
            'action' => 'Document updated',
            'action_type' => KycTimelineActionTypeEnum::DOCUMENT_STATE->value,
            'action_old_value' => $this->replaceWithValues($oldValues),
            'action_new_value' => $this->replaceWithValues($newValues),
            'document_id' => $document->id,
            'company_id' => $document->company_id,
            'applicant_id' => $document->applicant_id,
            'applicant_type' => $document->applicant_type,
        ]);
    }

    public function logApplicantSentEmailVerification(ApplicantIndividual $applicant): void
    {
        $this->createRow([
            'action' => 'Email verification link has been sent',
            'action_type' => KycTimelineActionTypeEnum::EMAIL->value,
            'company_id' => $applicant->company_id,
            'applicant_id' => $applicant->id,
        ]);
    }

    public function logApplicantSentEmailPasswordReset(ApplicantIndividual $applicant): void
    {
        $this->createRow([
            'action' => 'Email successful password reset has been sent',
            'action_type' => KycTimelineActionTypeEnum::EMAIL->value,
            'tag' => ModuleEnum::BANKING->toString(),
            'company_id' => $applicant->company_id,
            'applicant_id' => $applicant->id,
        ]);
    }

    public function logApplicantSentEmailRegistrationDetails(ApplicantIndividual $applicant): void
    {
        $this->createRow([
            'action' => 'Email registration details has been sent',
            'action_type' => KycTimelineActionTypeEnum::EMAIL->value,
            'company_id' => $applicant->company_id,
            'applicant_id' => $applicant->id,
        ]);
    }

    public function logApplicantSentEmailTrustedDeviceAdded(ApplicantIndividual $applicant): void
    {
        $this->createRow([
            'action' => 'Email new trusted device added has been sent',
            'action_type' => KycTimelineActionTypeEnum::EMAIL->value,
            'tag' => ModuleEnum::BANKING->toString(),
            'company_id' => $applicant->company_id,
            'applicant_id' => $applicant->id,
        ]);
    }

    public function logApplicantSentEmailTrustedDeviceRemoved(ApplicantIndividual $applicant): void
    {
        $this->createRow([
            'action' => 'Email trusted device removed has been sent',
            'action_type' => KycTimelineActionTypeEnum::EMAIL->value,
            'tag' => ModuleEnum::BANKING->toString(),
            'company_id' => $applicant->company_id,
            'applicant_id' => $applicant->id,
        ]);
    }

    public function logApplicantCompany(ApplicantCompany $applicantCompany, Members|null $member): void
    {
        $newValues = $this->getChanges($applicantCompany);
        if (empty($newValues)) {
            return;
        }

        if (array_key_exists('applicant_status_id', $newValues)) {
            $this->logApplicantCompanyStatus($applicantCompany, $member);

            return;
        }

        $oldValues = [];
        foreach ($newValues as $field => $newValue) {
            $oldValues[$field] = $applicantCompany->getOriginal($field);
        }

        $this->createRow([
            'creator_id' => $member->id ?? null,
            'action' => 'Company profile updated',
            'action_type' => KycTimelineActionTypeEnum::PROFILE->value,
            'action_old_value' => $this->replaceWithValues($oldValues),
            'action_new_value' => $this->replaceWithValues($newValues),
            'company_id' => $applicantCompany->company_id,
            'applicant_id' => $applicantCompany->id,
            'applicant_type' => class_basename(ApplicantCompany::class),
        ]);
    }

    public function logApplicantCompanyStatus(ApplicantCompany $applicantCompany, Members|null $member): void
    {
        $newValue = ['applicant_status_id' => $applicantCompany->getChanges()['applicant_status_id']];
        $oldValue = ['applicant_status_id' => $applicantCompany->getOriginal('applicant_status_id')];

        $this->createRow([
            'creator_id' => $member->id ?? null,
            'action' => 'Status changed',
            'action_type' => KycTimelineActionTypeEnum::VERIFICATION->value,
            'action_old_value' => $this->replaceWithValues($oldValue),
            'action_new_value' => $this->replaceWithValues($newValue),
            'company_id' => $applicantCompany->company_id,
            'applicant_id' => $applicantCompany->id,
            'applicant_type' => class_basename(ApplicantCompany::class),
        ]);
    }

    public function logApplicantCompanyNote(ApplicantCompanyNotes $applicantCompanyNote, Members|null $member): void
    {
        $applicant = $applicantCompanyNote->applicantIndividualCompany;

        $this->createRow([
            'creator_id' => $member->id ?? null,
            'action' => 'Comment added',
            'action_type' => KycTimelineActionTypeEnum::PROFILE->value,
            'company_id' => $applicantCompanyNote->applicantCompany->company_id,
            'applicant_id' => $applicant->applicant_id,
            'applicant_type' => $applicant->applicant_type,
        ]);
    }

    public function getChanges(ApplicantIndividual|ApplicantCompany $applicant): array
    {
        $allowedFields = [
            'first_name',
            'last_name',
            'middle_name',
            'email',
            'url',
            'phone',
            'is_verification_phone',
            'country_id',
            'language_id',
            'citizenship_country_id',
            'state',
            'city',
            'address',
            'zip',
            'nationality',
            'birth_country_id',
            'birth_state',
            'birth_city',
            'birth_at',
            'sex',
            'applicant_state_id',
            'applicant_state_reason_id',
            'applicant_risk_level_id',
            'account_manager_member_id',
            'company_id',
            'labels',
            'group_id',
            'module_ids',
            'project_id',
            'two_factor_auth_setting_id',
            'password_hash',
            'ip_address',
            'photo_id',
            'kyc_level_id',
        ];

        $newValues = $applicant->getChanges();

        return array_filter($newValues, function ($key) use ($allowedFields) {
            return in_array($key, $allowedFields);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getIp(): string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return request()->ip();
    }

    private function replaceWithValues(array $values): array|null
    {
        $result = null;
        foreach ($values as $key => $value) {
            if ($value === null) {
                continue;
            }

            switch ($key) {
                case 'applicant_status_id':
                    $result['applicant_status'] = ApplicantStatusEnum::tryFrom($value)->toString();
                    break;
                case 'applicant_risk_level_id':
                    $result['applicant_risk_level'] = ApplicantRiskLevelEnum::tryFrom($value)->toString();
                    break;
                case 'applicant_state_id':
                    $result['applicant_state'] = ApplicantStateEnum::tryFrom($value)->toString();
                    break;
                case 'applicant_state_reason_id':
                    $result['applicant_state_reason'] = ApplicantStateReasonEnum::tryFrom($value)->toString();
                    break;
                case 'document_state_id':
                    $result['document_state'] = DocumentStateEnum::tryFrom($value)->toString();
                    break;
                case 'birth_at':
                    $result['birth_at'] = Carbon::parse($value)->format('Y-m-d');
                    break;
                case 'password_hash':
                    $result['password'] = 'Changed';
                    unset($result['password_hash']);
                    break;
                case 'account_manager_member_id':
                    $result['account_manager'] = Members::find($value)->fullname ?? 'Unknown';
                    break;

                default:
                    $result[$key] = $value;
            }
        }

        return $result;
    }
}
