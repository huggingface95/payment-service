<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Company;
use App\Models\CompanyModule;

class CompanyModuleMutator extends BaseMutator
{

    public function attach($root, array $args): Company
    {
        $company = Company::find($args['company_id']);
        if (!$company) {
            throw new GraphqlException('Company does not exist', 'not found', 404);
        }

        if (isset($args['module_id'])) {
            CompanyModule::where('company_id',$args['company_id'])->delete();
            foreach ($args['module_id'] as $module) {
                CompanyModule::create([
                   'company_id' =>  $args['company_id'],
                    'module_id' => $module->id
                ]);
            }
        }

        return $company;
    }

    public function detach($root, array $args): Company
    {
        $company = Company::find($args['company_id']);
        $company->modules()->delete();

        return $company;
    }

}
