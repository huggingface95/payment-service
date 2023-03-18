<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository
 */
abstract class Repository
{
    public Builder $query;

    /**
     * @throws RepositoryException
     */
    public function __construct()
    {
        $this->query();
    }

    abstract protected function model(): string;

    /**
     * @throws RepositoryException
     */
    public function query(): Builder
    {
        return $this->query = $this->makeModel()->newQuery();
    }

    /**
     * @throws RepositoryException
     */
    public function makeModel(): Model
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model",
            );
        }

        return $model;
    }

    /**
     * @throws RepositoryException
     */
    public function find(array $conditions): Model|null
    {
        return $this->query()->where($conditions)->first();
    }
}
