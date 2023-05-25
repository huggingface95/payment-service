<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Fee;

class FeeMutator extends BaseMutator
{

    /**
     * @throws GraphqlException
     */
    public function attachFile($_, array $args): Fee
    {
        $fee = Fee::find($args['fee_id']);
        if (! $fee) {
            throw new GraphqlException('Fee not found', 'not found', 404);
        }

        $fee->files()->detach();
        $fee->files()->attach($args['file_id']);

        return $fee;
    }

    /**
     * @throws GraphqlException
     */
    public function detachFile($_, array $args): Fee
    {
        $fee = Fee::find($args['fee_id']);
        if (! $fee) {
            throw new GraphqlException('Fee not found', 'not found', 404);
        }

        $fee->files()->detach($args['file_id']);

        return $fee;
    }
}
