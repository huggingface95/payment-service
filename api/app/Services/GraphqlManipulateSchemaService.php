<?php

namespace App\Services;

use App\Repositories\Interfaces\GraphqlManipulateSchemaRepositoryInterface;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class GraphqlManipulateSchemaService
{
    protected bool $testMode;

    protected array $permissionsList;

    public function __construct(protected GraphqlManipulateSchemaRepositoryInterface $repository)
    {
        $this->testMode = env('APP_ENV') == 'testing';
    }

    /**
     * @throws DefinitionException
     */
    public function registerTestDocumentStateEnums(TypeRegistry $typeRegistry): TypeRegistry
    {
        return $typeRegistry->register(
            new EnumType([
                'name' => 'DocumentStateEnum',
                'description' => 'DocumentStateEnum',
                'values' => ['EMPTY' => ['value' => 'EMPTY', 'description' => 'EMPTY']],
            ])
        );
    }

    /**
     * @throws DefinitionException
     */
    public function registerDocumentStateEnums(TypeRegistry $typeRegistry): TypeRegistry
    {
        if ($this->testMode || ! $this->repository->hasDocumentStateTable()) {
            return $this->registerTestDocumentStateEnums($typeRegistry);
        } else {
            $enums = $this->repository->getDocumentStates();

            return $typeRegistry->register(
                new EnumType([
                    'name' => 'DocumentStateEnum',
                    'description' => 'DocumentStateEnum',
                    'values' => $enums,
                ])
            );
        }
    }

    /**
     * @throws DefinitionException
     */
    public function registerTestPermissionEnums(TypeRegistry $typeRegistry): TypeRegistry
    {
        $testEnum = new EnumType([
            'name' => 'PERMISSION_EMPTY',
            'description' => 'PERMISSION_EMPTY',
            'values' => ['EMPTY' => ['value' => 'EMPTY', 'description' => 'EMPTY']],
        ]);

        $testPermissionType = new ObjectType([
            'name' => 'PermissionType',
            'fields' => function () use ($testEnum): array {
                return ['PERMISSION_EMPTY' => ['type' => $testEnum]];
            },
        ]);

        $testPermissionAuth = new ObjectType([
            'name' => 'PermissionAuth',
            'fields' => function () use ($testEnum): array {
                return ['PERMISSION_EMPTY' => ['type' => new ListOfType($testEnum)]];
            },
        ]);

        $typeRegistry->register($testEnum);
        $typeRegistry->register($testPermissionType);
        $typeRegistry->register($testPermissionAuth);

        return $typeRegistry;
    }

    /**
     * @throws DefinitionException
     */
    public function registerPermissionEnums(TypeRegistry $typeRegistry): TypeRegistry
    {
        if ($this->testMode || ! $this->repository->hasPermissionsTable()) {
            return $this->registerTestPermissionEnums($typeRegistry);
        }

        $this->permissionsList = $this->repository->getAllPermissionsListWithClientType();

        $enums = [];
        foreach ($this->permissionsList as $k => $value) {
            $enums[$k] = new EnumType([
                'name' => $k,
                'description' => $k,
                'values' => $value,
            ]);
            $typeRegistry->register($enums[$k]);
        }

        $permissionsType = [];
        $permissionsAuth = [];
        foreach ($this->permissionsList as $k => $v) {
            $permissionsType[$k] = ['type' => $enums[$k]];
            $permissionsAuth[$k] = ['type' => new ListOfType($enums[$k])];
        }

        $typeRegistry->register(
            new ObjectType([
                'name' => 'PermissionType',
                'fields' => function () use ($permissionsType): array {
                    return $permissionsType;
                },
            ])
        );

        $typeRegistry->register(
            new ObjectType([
                'name' => 'PermissionAuth',
                'fields' => function () use ($permissionsAuth): array {
                    return $permissionsAuth;
                },
            ])
        );

        return $typeRegistry;
    }
}
