<?php

namespace App\Models;

/**
 * Class AccountClient
 */
class DepartmentPositionRelation extends BaseModel
{
    protected $table = 'department_position_relation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_id',
        'position_id',
    ];

    public $incrementing = false;
    public $timestamps = false;
}
