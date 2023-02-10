<?php

namespace App\Models;

use App\Models\Scopes\ModuleScope;

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

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ModuleScope());
    }
}
