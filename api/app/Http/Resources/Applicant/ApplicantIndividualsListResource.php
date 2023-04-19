<?php

namespace App\Http\Resources\Applicant;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ApplicantIndividualsListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'applicant_id' => $this->id,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'member_company' => isset($this->company?->name) ? Str::transliterate($this->company?->name) : '',
            'modules' => implode(' ', $this->modules?->pluck('name')->toArray()),
            'applicant_name' => isset($this->fullname) ? Str::transliterate($this->fullname) : '',
            'project_url' => $this->project?->url,
            'project_email' => $this->project?->support_email,
            'group_name' => $this->groupRole?->name,
            'risk_level' => $this->riskLevel?->name,
            'kyc_level' => $this->kycLevel?->name,
            'status' => $this->status?->name,
            'url' => $this->url,
        ];
    }
}
