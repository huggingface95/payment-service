<?php

namespace App\Models;

class DepartmentPosition extends BaseModel
{
    public $timestamps = false;

    protected $table = 'department_position';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'department_id', 'company_id',
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function members()
    {
        return $this->hasMany(Members::class);
    }

    public function company()
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public static function getPositionsIdByDepartment(int $departementId)
    {
        $positions = collect(self::where('department_id', $departementId)->get(['id']));

        return $positions->pluck('id')->all();
    }
}
