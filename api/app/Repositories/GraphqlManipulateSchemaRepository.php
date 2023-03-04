<?php

namespace App\Repositories;

use App\Models\ApplicantIndividual;
use App\Models\DocumentState;
use App\Models\Members;
use App\Models\Permissions;
use App\Models\PermissionsList;
use App\Repositories\Interfaces\GraphqlManipulateSchemaRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GraphqlManipulateSchemaRepository implements GraphqlManipulateSchemaRepositoryInterface
{

    protected \NumberFormatter $formatNumber;

    public function __construct(protected DocumentState $documentState, protected Permissions $permissions, protected PermissionsList $list)
    {
        $this->formatNumber = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
    }

    public function getDocumentStates(): array
    {
        return $this->documentState->newQuery()->orderBy('id')->get()->mapWithKeys(function ($r) {
            return [$this->toEnumFormat($r->name) => ['value' => $r->id, 'description' => $r->id]];
        })->toArray();
    }

    public function getAllPermissionsListWithClientType(?string $type, Members|ApplicantIndividual|null $user): array
    {
        if (!$type || !$user) {
            $permissionsList = collect(['empty' => collect([
                'empty' => 0
            ])]);
        } else {
            $permissionsList = $user->getAllPermissionsList();
        }

        return $this->keysToEnumFormat($permissionsList)->toArray();
    }


    private function keysToEnumFormat(Collection $list): Collection
    {
        return $list->mapWithKeys(function ($v, $k) {
            $v = $v->mapWithKeys(function ($v, $k) {
                return [$this->toEnumFormat($k) => $v];
            });
            return ['PERMISSION_' . $this->toEnumFormat($k) => $v];
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
