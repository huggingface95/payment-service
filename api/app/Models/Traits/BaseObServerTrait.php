<?php

namespace App\Models\Traits;

use App\Models\History;
use App\Observers\BaseObserver;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait BaseObServerTrait
{
    public static function bootBaseObServerTrait(): void
    {
        static::observe(method_exists(static::class, "getObServer")
            ? static::getObServer() : BaseObserver::class
        );
    }

    public function histories(): MorphMany
    {
        return $this->morphMany(History::class, 'model', 'model_type', 'model_id');
    }

    public function restoreLast(string $action = null): void
    {
        $lastHistory = $this->histories()->when($action, function ($q, $action) {
            return $q->where('action', '=', $action);
        })->latest('performed_at')->first();
        if ($lastHistory) {
            static::withoutEvents(function () use ($lastHistory) {
                return $this->update($lastHistory->meta);
            });
        }
    }

    public function getModelMeta(string $event): array
    {
        switch ($event) {
            case 'updating':
            case 'saving':
                return static::filterAttributes($this, array_intersect_key($this->getOriginal(), $this->getDirty()));
            case 'updated':
            case 'saved':
                $changes = $this->getDirty();
                return static::filterAttributes($this, $changes);
            case 'deleting':
            case 'deleted':
            case 'created':
                return static::filterAttributes($this, $this->attributes);
            default:
                return [];
        }
    }

    public static function filterAttributes($model, $data): array
    {
        return array_intersect_key($data, array_flip($model->getHistoryColumns()));
    }

}
