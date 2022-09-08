<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Region;
use Illuminate\Database\Eloquent\Collection;

class RegionMutator
{
    /**
     * @param $root
     * @param array $args
     * @return Region
     */
    public function create($root, array $args): Collection
    {
        Region::create($args);

        if (isset($args['query'])) {
            return Region::getAccountFilter($args['query'])->get();
        } else {
            return Region::get();
        }
    }
}
