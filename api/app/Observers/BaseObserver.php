<?php

namespace App\Observers;

use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\History;
use App\Models\Members;
use App\Models\Traits\CheckForEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseObserver
{
    use CheckForEvents;


    public function created(BaseModel|Model $model, bool $callHistory = true): bool
    {
        if ($callHistory)
            $this->checkAndCreateHistory($model, 'created');
        return true;
    }

    public function updated(BaseModel|Model $model, bool $callHistory = true): bool
    {
        if ($callHistory)
            $this->checkAndCreateHistory($model, 'updated');
        return true;
    }

    public function deleted(BaseModel|Model $model, bool $callHistory = true): bool
    {
        if ($callHistory)
            $this->checkAndCreateHistory($model, 'deleted');
        return true;
    }

    public function saved(BaseModel|Model $model, bool $callHistory = true): bool
    {
        if ($callHistory)
            $this->checkAndCreateHistory($model, 'saved');
        return true;
    }

    /**
     * @throws GraphqlException
     */
    public function creating(BaseModel|Model $model, bool $callHistory = true): bool
    {
        /** @var Members|ApplicantIndividual $user */
        $user = Auth::user();

        $success = self::filterByPermissionFilters($user, 'creating', $model)
            && self::filterByRoleActions($user, 'creating', $model)
            && self::filterByCompany($user, 'creating', $model)
            && self::checkSoftDeletedRecord('creating', $model);

        if ($success and $callHistory) {
            $this->checkAndCreateHistory($model, 'creating');
        }

        return $success;
    }

    /**
     * @throws GraphqlException
     */
    public function saving(BaseModel|Model $model, bool $callHistory = true): bool
    {
        /** @var Members|ApplicantIndividual $user */
        $user = Auth::user();

        $success = self::filterByPermissionFilters($user, 'saving', $model)
            && self::filterByRoleActions($user, 'saving', $model)
            && self::filterByCompany($user, 'saving', $model)
            && self::checkSoftDeletedRecord('saving', $model);

        if ($success and $callHistory) {
            $this->checkAndCreateHistory($model, 'saving');
        }

        return $success;
    }

    /**
     * @throws GraphqlException
     */
    public function updating(BaseModel|Model $model, bool $callHistory = true): bool
    {
        /** @var Members|ApplicantIndividual $user */
        $user = Auth::user();

        $success = self::filterByPermissionFilters($user, 'updating', $model)
            && self::filterByRoleActions($user, 'updating', $model)
            && self::filterByCompany($user, 'updating', $model)
            && self::checkSoftDeletedRecord('updating', $model);

        if ($success && $callHistory) {
            $this->checkAndCreateHistory($model, 'updating');
        }

        return $success;
    }

    /**
     * @throws GraphqlException
     */
    public function deleting(BaseModel|Model $model, bool $callHistory = true): bool
    {
        /** @var Members|ApplicantIndividual $user */
        $user = Auth::user();

        $success = self::filterByPermissionFilters($user, 'deleting', $model)
            && self::filterByRoleActions($user, 'deleting', $model)
            && self::filterByCompany($user, 'deleting', $model);

        if ($success && $callHistory) {
            $this->checkAndCreateHistory($model, 'deleting');
        }

        return $success;
    }

    protected function checkAndCreateHistory(BaseModel|Model $model, string $action): void
    {
        if (!method_exists($model, 'enableHistory') || !$model->enableHistory()) {
            return;
        }

        if (!static::filter($model, $action)) {
            return;
        }

        $model->morphMany(History::class, 'model')->create([
            'action' => $action,
            'meta' => $model->getModelMeta($action),
            'user_id' => static::getUserID(),
            'user_type' => static::getUserType(),
            'performed_at' => time(),
        ]);
    }

    private static function getUserID(): ?int
    {
        return Auth::user()->id ?? null;
    }

    private static function getUserType(): ?string
    {
        return Auth::user() ? get_class(Auth::user()) : null;
    }

    private static function filter(BaseModel $model, string $action): bool
    {
        return in_array($action, $model->getHistoryActions());
    }

}
