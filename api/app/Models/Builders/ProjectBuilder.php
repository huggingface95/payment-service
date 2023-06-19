<?php

namespace App\Models\Builders;

use App\Enums\ModuleEnum;
use App\Models\State;
use Illuminate\Database\Eloquent\Builder;

class ProjectBuilder extends Builder
{
    public function active($a): static
    {
        $this->where('module_id', '=', ModuleEnum::BANKING)->where('state_id', State::ACTIVE)->whereHas('accounts');
        return $this;
    }
}
