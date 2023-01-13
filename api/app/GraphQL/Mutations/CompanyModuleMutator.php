<?php

namespace App\GraphQL\Mutations;

use App\Models\Company;
use App\Models\CompanyModule;

class CompanyModuleMutator extends BaseMutator
{

    public function attach($root, array $args): Company
    {
        $company = Company::find($args['company_id']);

        if (isset($args['module_id'])) {
            $company->modules()->delete();
            foreach ($args['module_id'] as $module) {
                CompanyModule::insert([
                    'module_id' => $module,
                    'company_id' => $args['company_id'],
                    'is_active' => $args['is_active'] ?? false,
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

    public function update($root, array $args): Company
    {
        $company = Company::find($args['company_id']);

        if (isset($args['module_id'])) {
            foreach ($args['module_id'] as $module) {
                CompanyModule::where([
                    'company_id' => $args['company_id'],
                    'module_id' => $module,
                ])->update([
                    'is_active' => $args['is_active'],
                ]);
            }
        }

        return $company;
    }
}
