<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Region;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RegionMutator
{
    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): LengthAwarePaginator
    {
        Region::create($args);

        if (isset($args['query'])) {
            return Region::getAccountFilter($args['query'])->get();
        } else {
            return Region::get();
        }
    }
}
