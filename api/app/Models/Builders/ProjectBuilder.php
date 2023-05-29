<?php

namespace App\Models\Builders;

use App\Enums\ModuleEnum;
use Illuminate\Database\Eloquent\Builder;

class ProjectBuilder extends Builder
{
    public function active($a): static
    {
        $this->whereHas('accounts')->where('module_id', '=', ModuleEnum::BANKING);

        return $this;
    }
}
