<?php

namespace App\Services;

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
        $newValues = $applicantIndividual->getChanges();
        unset($newValues['updated_at']);

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
            'action_old_value' => $oldValues,
            'action_new_value' => $newValues,
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
            'action_old_value' => $oldValue,
            'action_new_value' => $newValue,
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
            'action_old_value' => $oldValues,
            'action_new_value' => $newValues,
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
        $newValues = $applicantCompany->getChanges();
        unset($newValues['updated_at']);

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
            'action_old_value' => $oldValues,
            'action_new_value' => $newValues,
            'company_id' => $applicantCompany->id,
            'applicant_id' => $applicantCompany->applicantIndividualCompany->applicant_id,
            'applicant_type' => $applicantCompany->applicantIndividualCompany->applicant_type,
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
            'action_old_value' => $oldValue,
            'action_new_value' => $newValue,
            'company_id' => $applicantCompany->id,
            'applicant_id' => $applicantCompany->applicantIndividualCompany->applicant_id,
            'applicant_type' => $applicantCompany->applicantIndividualCompany->applicant_type,
        ]);
    }

    public function logApplicantCompanyNote(ApplicantCompanyNotes $applicantCompanyNote, Members|null $member): void
    {
        $applicant = $applicantCompanyNote->applicantIndividualCompany;
        
        $this->createRow([
            'creator_id' => $member->id ?? null,
            'action' => 'Comment added',
            'action_type' => KycTimelineActionTypeEnum::PROFILE->value,
            'company_id' => $applicantCompanyNote->applicant_company_id,
            'applicant_id' => $applicant->applicant_id,
            'applicant_type' => $applicant->applicant_type,
        ]);
    }

    public function getIp(): string
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
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
}
