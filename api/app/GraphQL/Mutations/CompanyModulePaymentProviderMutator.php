<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\CompanyModule;
use App\Models\CompanyModulePaymentProvider;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Support\Facades\Crypt;

class CompanyModulePaymentProviderMutator extends BaseMutator
{
    public function create($root, array $args): CompanyModulePaymentProvider
    {
        $companyModule = CompanyModule::find($args['company_module_id']);
        if (!$companyModule) {
            throw new GraphqlException('Company module not found', 'not found', 404);
        }

        try {
            if (!empty($args['password'])) {
                $args['password'] = Crypt::encryptString($args['password']);
            }
        } catch (EncryptException $e) {
            throw new GraphqlException('Encryption data error', 'internal');
        }

        $provider = $companyModule->paymentProviders()->create($args);

        return $provider;
    }

    public function update($root, array $args): CompanyModulePaymentProvider
    {
        $provider = CompanyModulePaymentProvider::find($args['id']);
        if (!$provider) {
            throw new GraphqlException('Company module payment provider not found', 'not found', 404);
        }

        try {
            if (!empty($args['password'])) {
                $args['password'] = Crypt::encryptString($args['password']);
            }
        } catch (EncryptException $e) {
            throw new GraphqlException('Encryption data error', 'internal');
        }

        $provider->update($args);

        return $provider;
    }
}