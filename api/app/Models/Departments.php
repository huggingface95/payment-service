<?php

namespace App\Models;

class Departments extends BaseModel
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'company_id',
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Companies', 'company_id');
    }

    public function positions()
    {
        return $this->belongsToMany(DepartmentPosition::class, 'department_position_relation', 'department_id', 'position_id');
    }

    public function setActive($active = true)
    {
        $this->positions()->update(['is_active'=>$active]);
    }
}
