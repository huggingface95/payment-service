<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * Class MemberAccessLimitation
 * @package App\Models
 * @property int id
 * @property int member_id
 * @property int group_id
 * @property int group_role_id
 * @property int provider_id
 * @property int commission_template_id
 *
 * @property Collection groupRoles
 * @property GroupRole $groupRole
 * @property EmailSmtp $smtp
 *
 */
class MemberAccessLimitation extends BaseModel
{
    protected $fillable = ['member_id', 'group_id', 'group_role_id', 'provider_id', 'commission_template_id'];


    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Groups::class, 'group_id');
    }

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_role_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'provider_id');
    }

    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class, 'commission_template_id');
    }

    public function clients()
    {

    }

}
