<?php

namespace App\Repositories\Interfaces;

interface GraphqlManipulateSchemaRepositoryInterface
{

    public function getDocumentStates(): array;

    public function getAllPermissionsListWithClientType(): array;

}
