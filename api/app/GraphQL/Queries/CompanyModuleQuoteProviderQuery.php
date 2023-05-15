<?php

namespace App\GraphQL\Queries;

use App\Exceptions\GraphqlException;
use App\Models\CompanyModuleQuoteProvider;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class CompanyModuleQuoteProviderQuery
{
    /**
     * @throws GraphqlException
     */
    public function getPassword($root, array $args): array
    {
        $provider = CompanyModuleQuoteProvider::query()->find($args['id']);
        if (! $provider) {
            throw new GraphqlException('Company module QUOTE provider not found', 'not found', 404);
        }

        try {
            if (! empty($provider->password)) {
                $decryptedPassword = Crypt::decryptString($provider->password);
            }
        } catch (DecryptException $e) {
            throw new GraphqlException('Decryption data error', 'internal');
        }

        return [
            'id' => $provider->id,
            'password' => $decryptedPassword,
        ];
    }
}
