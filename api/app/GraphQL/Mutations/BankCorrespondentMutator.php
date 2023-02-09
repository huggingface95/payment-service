<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\BankCorrespondent;

class BankCorrespondentMutator extends BaseMutator
{
    /**
     * @param    $_
     * @param  array  $args
     * @return mixed
     */
    public function create($_, array $args)
    {
        $bank = BankCorrespondent::create($args);

        if (isset($args['currency_id'])) {
            $bank->bankCorrespondentCurrencies()->delete();

            foreach ($args['currency_id'] as $id) {
                $bank->bankCorrespondentCurrencies()->create(['currency_id' => $id]);
            }
        }

        if (isset($args['region_id'])) {
            $bank->bankCorrespondentRegions()->delete();

            foreach ($args['region_id'] as $id) {
                $bank->bankCorrespondentRegions()->create(['region_id' => $id]);
            }
        }

        return $bank;
    }

    /**
     * @param    $_
     * @param  array  $args
     * @return mixed
     */
    public function update($_, array $args)
    {
        $bank = BankCorrespondent::find($args['id']);
        if (! $bank) {
            throw new GraphqlException('Not found', 'not found', 404);
        }
        $bank->update($args);

        if (isset($args['currency_id'])) {
            $bank->bankCorrespondentCurrencies()->delete();

            foreach ($args['currency_id'] as $id) {
                $bank->bankCorrespondentCurrencies()->create(['currency_id' => $id]);
            }
        }

        if (isset($args['region_id'])) {
            $bank->bankCorrespondentRegions()->delete();

            foreach ($args['region_id'] as $id) {
                $bank->bankCorrespondentRegions()->create(['region_id' => $id]);
            }
        }

        return $bank;
    }
}
