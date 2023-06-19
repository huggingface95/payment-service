<?php

namespace App\GraphQL\Mutations;

use App\Enums\ModuleEnum;
use App\Exceptions\GraphqlException;
use App\Models\Company;
use App\Models\CompanyModule;
use App\Models\CompanyModuleIbanProvider;
use App\Models\CompanyModulePaymentProvider;
use App\Models\CompanyModuleQuoteProvider;
use App\Models\PaymentProvider;
use App\Models\PaymentProviderIban;
use App\Models\Project;
use App\Models\QuoteProvider;
use Illuminate\Support\Facades\DB;

class CompanyModuleMutator extends BaseMutator
{
    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): Company
    {
        try {
            DB::beginTransaction();
            $company = Company::find($args['company_id']);
            if (!$company) {
                throw new GraphqlException('Company does not exist', 'not found', 404);
            }

            if (isset($args['module_id'])) {
                $this->addModules($company, $args['module_id']);
            }

            if (!isset($args['module_id']) || empty($args['module_id'])) {
                $company->modules()->where('module_id', '<>', ModuleEnum::KYC->value)->delete();
            }


            DB::commit();

            return $company;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new GraphqlException($exception->getMessage(), $exception->getCode());
        }
    }

    public function detach($root, array $args): Company
    {
        /** @var Company $company */
        $company = Company::find($args['company_id']);
        $company->modules()->where('module_id', '<>', ModuleEnum::KYC->value)->delete();

        return $company;
    }

    private function addModules(Company $company, array $modules): void
    {
        $ids = [collect($modules)->crossJoin('module_id')->map(function ($m) {
            return [$m[1] => $m[0]];
        })->toArray(),
            [['module_id' => ModuleEnum::KYC->value]],
        ];

        collect($ids)->flatten(1)->unique(function ($item) {
            return $item['module_id'];
        })->each(function ($module) use ($company) {
            /** @var CompanyModule $companyModule */
            $companyModule = $company->modules()->firstOrCreate($module);
            $this->createProviders($companyModule, $company);
        });
    }

    private function createProviders(CompanyModule $companyModule, Company $company): void
    {
        $companyModule->paymentProviders()->saveMany($company->paymentProviders->map(function (PaymentProvider $p) use ($companyModule) {
            return new CompanyModulePaymentProvider([
                'payment_provider_id' => $p->id,
                'is_active' => $companyModule->is_active ?? false,
            ]);
        }));
        $companyModule->ibanProviders()->saveMany($company->paymentProvidersIban->map(function (PaymentProviderIban $p) use ($companyModule) {
            return new CompanyModuleIbanProvider([
                'payment_provider_iban_id' => $p->id,
                'is_active' => $companyModule->is_active ?? false,
            ]);
        }));

        $companyModule->quoteProviders()->saveMany($company->quoteProviders->map(function (QuoteProvider $p) use ($companyModule) {
            return new CompanyModuleQuoteProvider([
                'quote_provider_id' => $p->id,
                'is_active' => $companyModule->is_active ?? false,
            ]);
        }));
    }
}
