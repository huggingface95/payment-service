<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends BaseModel
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

    public function company(): BelongsTo
    {
        return $this->belongsTo('App\Models\Companies', 'company_id');
    }

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(DepartmentPosition::class, 'department_position_relation', 'department_id', 'position_id');
    }

    public function setActive($active = true)
    {
        $this->positions()->update(['is_active'=>$active]);
    }
}
