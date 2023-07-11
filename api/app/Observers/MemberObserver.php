<?php

namespace App\Observers;

use App\Enums\ClientTypeEnum;
use App\Models\Members;
use App\Models\PermissionsList;
use Illuminate\Database\Eloquent\Model;

class MemberObserver extends BaseObserver
{
    public function created(Members|Model $model, bool $callHistory = false): bool
    {
        parent::created($model);

        if (!$model->is_sign_transaction) {
            if ($list = PermissionsList::query()->with('permissions')
                ->where('name', '=', PermissionsList::SIGN_PAYMENTS)
                ->where('type', '=', ClientTypeEnum::MEMBER->toString())
                ->first()) {
                foreach ($list->permissions as $p) {
                    $model->permissionLimitations()->attach(['permission_id' => $p->id]);
                }
            }
        }
        return true;
    }

    public function updated(Members|Model $model, bool $callHistory = false): bool
    {
        parent::updated($model, $callHistory);

        if (array_key_exists('is_sign_transaction', $model->getChanges())) {
            $model->permissionLimitations()->sync([]);
            if (!$model->is_sign_transaction) {
                if ($list = PermissionsList::query()->with('permissions')
                    ->where('name', '=', PermissionsList::SIGN_PAYMENTS)
                    ->where('type', '=', ClientTypeEnum::MEMBER->toString())
                    ->first()) {
                    foreach ($list->permissions as $p) {
                        $model->permissionLimitations()->attach(['permission_id' => $p->id]);
                    }
                }
            }
        }
        return true;
    }
}
