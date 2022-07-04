<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;

/**
 * Class MemberAccessLimitation
 *
 * @property int id
 * @property int member_id
 * @property int group_role_id
 * @property int commission_template_id
 * @property Collection groupRoles
 * @property GroupRole $groupRole
 * @property EmailSmtp $smtp
 */
class MemberAccessLimitation extends BaseModel
{
    protected $fillable = ['member_id', 'group_role_id', 'commission_template_id'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function group(): HasOneThrough
    {
        return $this->hasOneThrough(
            GroupType::class,
            GroupRole::class,
            'id',
            'id',
            'group_role_id',
            'group_type_id'
        );
    }

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_role_id');
    }

    public function provider(): HasOneThrough
    {
        return $this->hasOneThrough(
            PaymentProvider::class,
            CommissionTemplate::class,
            'id',
            'id',
            'commission_template_id',
            'payment_provider_id'
        );
    }

    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class, 'commission_template_id');
    }
}
