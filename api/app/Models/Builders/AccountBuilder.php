<?php

namespace App\Models\Builders;

use App\Models\AccountState;
use Illuminate\Database\Eloquent\Builder;

class AccountBuilder extends Builder
{
    public function active($a): static
    {
        $this->where('account_state_id', '=', AccountState::ACTIVE);

        return $this;
    }
}
