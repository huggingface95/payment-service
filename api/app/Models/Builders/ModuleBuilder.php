<?php

namespace App\Models\Builders;

use App\Enums\ModuleEnum;
use Illuminate\Database\Eloquent\Builder;

class ModuleBuilder extends Builder
{
    public function withoutKYC($a): static
    {
        $this->where('id', '<>', ModuleEnum::KYC->value);
        return $this;
    }
}
