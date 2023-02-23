<?php

namespace App\Providers;

use App\Models\DocumentState;
use GraphQL\Type\Definition\EnumType;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class DocumentStateToEnumServiceProvider extends ServiceProvider
{
    /**
     * @throws DefinitionException
     */
    public function boot(TypeRegistry $typeRegistry): void
    {
        $values = DocumentState::query()->orderBy('id')->get()->mapWithKeys(function ($r) {
            return [$r->name => ['value' => $r->id, 'description' => $r->id]];
        })->toArray();

        $typeRegistry->register(
            new EnumType([
                'name' => 'DocumentStateEnum',
                'description' => 'DocumentStateEnum',
                'values' => $values,
            ])
        );
    }
}
