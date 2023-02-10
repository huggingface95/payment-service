<?php

namespace App\Models;

use App\Models\Builders\ModuleBuilder;

class Module extends BaseModel
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function newEloquentBuilder($builder): ModuleBuilder
    {
        return new ModuleBuilder($builder);
    }
}
