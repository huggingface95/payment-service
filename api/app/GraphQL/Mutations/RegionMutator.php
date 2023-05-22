<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Region;
use Illuminate\Support\Facades\DB;

class RegionMutator
{
    public function create($_, array $args)
    {
        $region = Region::create($args);

        if (isset($args['countries'])) {
            $region->countries()->sync($args['countries']['sync'], true);
        }

        return $region;
    }

    public function update($_, array $args)
    {
        $region = Region::find($args['id']);

        if (! $region) {
            throw new GraphqlException('Region not found', 'not found', 404);
        }

        $region->update($args);

        $region->countries()->sync($args['countries']['sync'], true);

        return $region;
    }
}
