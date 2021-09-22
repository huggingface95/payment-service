<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentPosition extends Model
{
    public $timestamps = false;
    protected $table = 'department_position';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


    public function department()
    {
        return $this->belongsTo(Departments::class,'department_id');
    }

}
