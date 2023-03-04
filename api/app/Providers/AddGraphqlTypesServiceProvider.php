<?php

namespace App\Providers;


use App\Services\GraphqlManipulateSchemaService;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class AddGraphqlTypesServiceProvider extends ServiceProvider
{

    /**
     * @throws DefinitionException
     */
    public function boot(TypeRegistry $typeRegistry, GraphqlManipulateSchemaService $service): void
    {
        $service->registerDocumentStateEnums($typeRegistry);
        $service->registerPermissionEnums($typeRegistry);
    }
}
