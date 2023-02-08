<?php

namespace App\Models;

/**
 * Class TicketStatus
 */
class TicketStatus extends BaseModel
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
}
