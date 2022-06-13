<?php
namespace App\GraphQL\Handlers;


use App\Models\Groups;
use Doctrine\DBAL\Exception;
use Illuminate\Database\Eloquent\Builder;

class EmailNotificationClientableHandler {

    /**
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $builder
     * @param  array<string, mixed>  $whereConditions
     */
    public function __invoke(Builder $builder, array $whereConditions): void
    {
        if (count($builder->getBindings()) == 3){
            $group = Groups::find(last($builder->getBindings()));
            $relation = null;
            $condition = $whereConditions['HAS']['condition'];
            if($group->name == Groups::MEMBER)
                $relation = 'member';
            elseif ($group->name == Groups::COMPANY)
                $relation = 'applicantCompany';
            elseif ($group->name == Groups::INDIVIDUAL)
                $relation = 'applicantIndividual';

            $builder->whereHas($relation, function (Builder $q) use ($condition){
                $q->where($q->getModel()->getTable().".".$condition['column'], $condition['operator'], $condition['value']);
            });
        }
    }
}
