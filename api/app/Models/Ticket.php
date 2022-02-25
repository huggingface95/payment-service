<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;


/**
 * Class Ticket
 * @package App\Models
 * @property int id
 * @property int member_id
 * @property int client_id
 * @property string title
 * @property string message
 * @property int status
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property TicketComments comments
 * @property Members member
 * @property ApplicantIndividual client
 * @property Companies company
 * @property DepartmentPosition position
 * @property Departments department
 *
 */
class Ticket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id', 'client_id', 'title', 'message', 'status'
    ];


    public function comments(): HasMany
    {
        return $this->hasMany(TicketComments::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividual::class, 'client_id');
    }


    public function company(): HasOneThrough
    {
        return $this->hasOneThrough(
            Companies::class,
            Members::class,
            'id',
            'id',
            'member_id',
            'company_id',
        );
    }

    public function position(): HasOneThrough
    {
        return $this->hasOneThrough(
            DepartmentPosition::class,
            Members::class,
            'id',
            'id',
            'member_id',
            'department_position_id',
        );
    }

    public function file(): HasOne
    {
        return $this->hasOne(Files::class, 'author_id')->where('entity_type', self::class);
    }

    public function getDepartmentAttribute()
    {
        return $this->position()
            ->join('department_position_relation', 'department_position_relation.position_id', '=', 'department_position.id')
            ->join('departments', 'departments.id', '=', 'department_position_relation.department_id')
            ->select('departments.*')
            ->first();
    }
}
