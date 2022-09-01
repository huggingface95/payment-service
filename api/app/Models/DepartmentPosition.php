<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Members::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public static function getPositionsIdByDepartment(int $departementId): array
    {
        $positions = collect(self::where('department_id', $departementId)->get(['id']));

        return $positions->pluck('id')->all();
    }
}
