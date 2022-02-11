<?php
namespace App\GraphQL\Handlers;



class BetweenDateHandler {
    /**
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $builder
     * @param  array<string, mixed>  $whereConditions
     */
    public function __invoke(object $builder, array $whereConditions): void
    {
        if ($whereConditions['column'] == 'created_at') {
            $date = explode(',',$whereConditions['value']);
            $builder->whereBetween($whereConditions['column'],[$date[0],$date[1]." 23:59:59"]);
        }
    }
}
