<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * Class MemberAccessLimitation
 *
 * @property int id
 * @property int member_id
 * @property int group_role_id
 * @property int company_id
 * @property int module_id
 * @property int project_id
 * @property int payment_provider_id
 * @property Collection groupRoles
 * @property GroupRole $groupRole
 * @property EmailSmtp $smtp
 */
class MemberAccessLimitation extends BaseModel
{
    protected $fillable = [
        'member_id',
        'group_role_id',
        'company_id',
        'module_id',
        'project_id',
        'payment_provider_id',
        'see_own_applicants',
        'group_type_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
    }

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_role_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }
}
