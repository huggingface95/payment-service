<?php

namespace App\Observers;

use App\Models\ProjectSettings;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectSettingsObserver extends BaseObserver
{

    public function creating(ProjectSettings|BaseModel|Model $model, bool $callHistory = false): bool
    {
        if (!parent::creating($model, $callHistory)) {
            return false;
        }

        $model->setAttribute('secret_key', Str::random());

        return true;
    }

}
