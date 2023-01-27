<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\CompanyModule;
use App\Models\CompanyModulePaymentProvider;

class CompanyModulePaymentProviderMutator extends BaseMutator
{
    public function create($root, array $args): CompanyModulePaymentProvider
    {
        $companyModule = CompanyModule::find($args['company_module_id']);
        if (!$companyModule) {
            throw new GraphqlException('Company module not found', 'not found', 404);
        }

        return $companyModule->paymentProviders()->create($args);
    }

    /**
     * @throws GraphqlException
     */
    public function update($root, array $args): CompanyModulePaymentProvider
    {
        /** @var CompanyModulePaymentProvider $provider */
        $provider = CompanyModulePaymentProvider::find($args['id']);
        if (!$provider) {
            throw new GraphqlException('Company module payment provider not found', 'not found', 404);
        }

        if ($args['is_active']) {
            //TODO add logic
        } else {
            $provider->projectApiSettings()->delete();
        }

        $provider->update($args);

        return $provider;
    }
}
