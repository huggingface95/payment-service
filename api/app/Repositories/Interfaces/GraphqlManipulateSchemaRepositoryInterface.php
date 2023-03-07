<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface GraphqlManipulateSchemaRepositoryInterface
{

    public function getDocumentStates(): array;

    public function getAllPermissionsListWithClientType(): array;

    public function getAllPermissionsList(?string $type): Collection;

}
