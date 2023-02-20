<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\CompanyModule;
use App\Models\MemberAccessLimitation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MemberAccessLimitationMutator
{
    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): MemberAccessLimitation
    {
        try {
            CompanyModule::query()->where([
                ['company_id', $args['company_id']], ['module_id', $args['module_id']], ['is_active', true]
            ])->firstOrFail();

            return MemberAccessLimitation::create($args);

        } catch (ModelNotFoundException) {
            throw new GraphqlException('Module not found or disabled for this company', 'use');
        } catch (\Throwable $e) {
            throw new GraphqlException($e->getMessage(), 'use');
        }

    }
}
