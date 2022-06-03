<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
class TicketComments extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id', 'client_id', 'message'
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope(parent::getApplicantIdsByAuthMember()));
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividual::class, 'client_id');
    }

    public function file(): HasOne
    {
        return $this->hasOne(Files::class, 'author_id')->where('entity_type', self::class);
    }
}
