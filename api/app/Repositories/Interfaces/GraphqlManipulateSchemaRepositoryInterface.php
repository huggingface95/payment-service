<?php

namespace App\Repositories\Interfaces;

use App\Models\ApplicantIndividual;
use App\Models\Members;

interface GraphqlManipulateSchemaRepositoryInterface
{

    public function getDocumentStates(): array;

    public function getAllPermissionsListWithClientType(?string $type, Members|ApplicantIndividual|null $user): array;

}
