<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\CompanyLedgerSettings;

class CompanyLedgerSettingsMutator extends BaseMutator
{
    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): CompanyLedgerSettings
    {
        $companyLedgerSettings = CompanyLedgerSettings::find($args['company_id']);
        if (! $companyLedgerSettings) {
            throw new GraphqlException('Company ledger settings not found', 'not found', 404);
        }

        $companyLedgerSettings->update($args);

        return $companyLedgerSettings;
    }

    /**
     * @throws GraphqlException
     */
    public function delete($_, array $args): bool
    {
        $companyLedgerSettings = CompanyLedgerSettings::find($args['company_id']);
        if (! $companyLedgerSettings) {
            throw new GraphqlException('Company ledger settings not found', 'not found', 404);
        }

        $companyLedgerSettings->delete();

        return true;
    }
}
