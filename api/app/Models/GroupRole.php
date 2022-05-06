<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupRole extends Model
{
    use SoftDeletes;

    const INDIVIDUAL = 'Individual';
    const COMPANY = 'Company';
    const MEMBER = 'Member';

    public $timestamps = false;
    protected $table = 'group_role';

    protected $fillable = [
        'name','group_type_id', 'role_id','payment_provider_id','commission_template_id','is_active','description','company_id'
    ];

    public function groupType(): BelongsTo
    {
        return $this->belongsTo(Groups::class,"group_type_id");
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class,"role_id");
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class,"payment_provider_id");
    }

    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class,"commission_template_id");
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class,"company_id");
    }

    public function individuals(): HasManyThrough
    {
       return $this->hasManyThrough(
            ApplicantIndividual::class,
            'group_role_members_individuals',
            'group_role_id',
            'id',
            'id',
            'user_id',
        );
    }

    public function companies(): HasManyThrough
    {
        return $this->hasManyThrough(
            ApplicantCompany::class,
            'group_role_members_individuals',
            'group_role_id',
            'id',
            'id',
            'user_id',
        );
    }

    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(
            Members::class,
            'group_role_members_individuals',
            'group_role_id',
            'id',
            'id',
            'user_id',
        );
    }

    /** Dynamic call */
    public function users(): HasManyThrough
    {
        $type = $this->groupType()->first();
        if ($type == self::INDIVIDUAL)
            return $this->individuals();
        elseif ($type == self::COMPANY)
            return $this->companies();
        else
            return $this->members();
    }


}
