<?php

namespace App\Models\Builders;

use App\Enums\GuardEnum;
use Illuminate\Database\Eloquent\Builder;

class TransferBuilder extends Builder
{
    public function filterByAuthUser($args): static
    {

        $this->where(function (Builder $q) {
            $applicant = auth()->user();
            $q->where(function (Builder $q) use ($applicant) {
                $q->where('client_to_id', '=', $applicant->id)
                    ->where('client_to_type', '=', GuardEnum::GUARD_INDIVIDUAL->toString());
            })->orWhere(function (Builder $q) use ($applicant) {
                $q->where('client_from_id', '=', $applicant->id)
                    ->where('client_from_type', '=', GuardEnum::GUARD_INDIVIDUAL->toString());
            });
        });

        return $this;
    }
}
