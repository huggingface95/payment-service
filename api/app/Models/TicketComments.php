<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class TicketComment
 * @package App\Models
 * @property int id
 * @property int ticket_id
 * @property int client_id
 * @property string message
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Ticket ticket
 * @property ApplicantIndividual client
 *
 */
class TicketComments extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id', 'client_id', 'message'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividual::class, 'client_id');
    }
}
