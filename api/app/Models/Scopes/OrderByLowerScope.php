<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class OrderByLowerScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $model = $builder->getModel();
        $orders = $builder->getQuery()->orders;
        $builder->reorder();

        foreach ($orders as $order){
            try {
                $type = Schema::getColumnType($model->getTable(), $order['column']);
                if ($type == 'string') {
                    $builder->orderByRaw("lower({$order['column']})  {$order['direction']}");
                    $builder->orderByRaw("{$order['column']}  {$order['direction']}");
                }
                else {
                    throw new \Exception('Undefined column in table or other type column');
                }
            }
            catch (\Throwable){
                $builder->orderBy($order['column'], $order['direction']);
            }
        }
    }
}
