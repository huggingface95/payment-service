<?php

namespace App\GraphQL\Mutations\Traits;

use App\Enums\ClientTypeEnum;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait GetMemberOrIndividualClickhouseTrait
{
    public function addClient(LengthAwarePaginator $result): void
    {
        $result->getCollection()->transform(function ($value) {
            if ($value['provider'] == ClientTypeEnum::APPLICANT->toString()) {
                $value['client'] = ApplicantIndividual::query()->where('email', $value['email'])->first();
            } elseif ($value['provider'] == ClientTypeEnum::MEMBER->toString()) {
                $value['client'] = Members::query()->where('email', $value['email'])->first();
            } else {
                $value['client'] = null;
            }
            $value['client_type'] = $value['provider'];

            return $value;
        });
    }
}
