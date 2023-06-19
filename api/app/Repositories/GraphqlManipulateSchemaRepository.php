<?php

namespace App\Repositories;

use App\Models\DocumentState;
use App\Models\Permissions;
use App\Models\PermissionsList;
use App\Repositories\Interfaces\GraphqlManipulateSchemaRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GraphqlManipulateSchemaRepository implements GraphqlManipulateSchemaRepositoryInterface
{
    protected \NumberFormatter $formatNumber;

    public function __construct(protected DocumentState $documentState, protected Permissions $permissions, protected PermissionsList $list)
    {
        $this->formatNumber = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
    }

    public function hasDocumentStateTable(): bool
    {
        return Schema::hasTable($this->documentState->getTable());
    }

    public function hasPermissionsTable(): bool
    {
        return Schema::hasTable($this->permissions->getTable());
    }

    public function getDocumentStates(): array
    {
        return $this->documentState->newQuery()->orderBy('id')->get()->mapWithKeys(function ($r) {
            return [$this->toEnumFormat($r->name) => ['value' => $r->id, 'description' => $r->id]];
        })->toArray();
    }

    public function getAllPermissionsListWithClientType(): array
    {
        $type = Auth::guard('api')->type() ?? Auth::guard('api_client')->type();

        $permissionsList = $this->getAllPermissionsList($type);

        return $this->keysToEnumFormat($permissionsList)->toArray();
    }

    public function getAllPermissionsList(?string $type): Collection
    {
        return PermissionsList::query()->with('permissions')->when($type, function ($q, $t) {
            return $q->where('type', $t);
        })->get()->pluck('permissions', 'name')->map(function ($permissions) {
            return $permissions->pluck('id', 'display_name');
        });
    }

    private function keysToEnumFormat(Collection $list): Collection
    {
        return $list->mapWithKeys(function ($v, $k) {
            $v = $v->mapWithKeys(function ($v, $k) {
                return [$this->toEnumFormat($k) => $v];
            });

            return ['PERMISSION_'.$this->toEnumFormat($k) => $v];
        });
    }

    private function toEnumFormat(string $str): string
    {
        $str = preg_replace_callback("/\d/", function ($match) {
            return $this->formatNumber->format($match[0]);
        }, $str);

        return strtoupper(Str::snake(preg_replace("/(\/)|(&)|(\()|(\))|(:)/", '', $str)));
    }
}
