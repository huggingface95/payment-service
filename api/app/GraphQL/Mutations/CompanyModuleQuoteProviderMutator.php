<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\CompanyModule;
use App\Models\CompanyModuleQuoteProvider;
use App\Models\ProjectApiSetting;
use Illuminate\Database\Eloquent\Model;

class CompanyModuleQuoteProviderMutator extends BaseMutator
{
    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): CompanyModuleQuoteProvider|Model
    {
        /** @var CompanyModule $companyModule */
        $companyModule = CompanyModule::query()->find($args['company_module_id']);
        if (! $companyModule) {
            throw new GraphqlException('Company module not found', 'not found', 404);
        }

        return $companyModule->quoteProviders()->create($args);
    }

    /**
     * @throws GraphqlException
     */
    public function update($root, array $args): CompanyModuleQuoteProvider|Model
    {
        /** @var CompanyModuleQuoteProvider $companyModuleProvider */
        $companyModuleProvider = CompanyModuleQuoteProvider::query()->find($args['id']);
        if (! $companyModuleProvider) {
            throw new GraphqlException('Company module QUOTE provider not found', 'not found', 404);
        }

        if ($args['is_active']) {
            //if not exist`s project ids to project_api_settings add with nullable params
            $companyModuleProvider->projects()->get()->unique()->pluck('id')->crossJoin('project_id')->each(function ($item) {
                ProjectApiSetting::firstOrCreate([$item[1] => $item[0]]);
            });
        } else {
            $companyModuleProvider->projectApiSettings()->delete();
        }

        $companyModuleProvider->update($args);

        return $companyModuleProvider;
    }
}
