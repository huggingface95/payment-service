<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSettings extends BaseModel
{

    use BaseObServerTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'group_type_id',
        'group_role_id',
        'commission_template_id',
        'payment_provider_id',
        'iban_provider_id',
        'quote_provider_id',
        'applicant_type',
    ];

    public $timestamps = false;

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_role_id');
    }

    public function groupType(): BelongsTo
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class, 'commission_template_id');
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    public function ibanProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProviderIban::class, 'iban_provider_id');
    }

    public function quoteProvider(): BelongsTo
    {
        return $this->belongsTo(QuoteProvider::class, 'quote_provider_id');
    }
}
