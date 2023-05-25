<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\RoleFilterSuperAdminScope;
use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * Class ApplicantBankingAccess
 *
 * @property float daily_limit
 * @property float used_daily_limit
 * @property float used_monthly_limit
 * @property float monthly_limit
 * @property float operation_limit
 * @property float used_limit
 */
class ApplicantBankingAccess extends BaseModel
{
    use BaseObServerTrait;


    use HasFactory;
    use HasRelationships;

    public $day_used_limit = 0;

    public $month_used_limit = 0;

    protected $table = 'applicant_banking_access';

    private array $permissionsList;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id',
        'applicant_company_id',
        'member_id',
        'contact_administrator',
        'daily_limit',
        'monthly_limit',
        'operation_limit',
        'used_limit',
        'used_daily_limit',
        'used_monthly_limit',
        'role_id',
        'grant_access',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }

    public function getCreatePaymentsAttribute(): bool
    {
        if (! isset($this->permissionsList)) {
            $this->permissionsList = $this->permissions()->pluck('upname')->toArray();
        }

        return in_array('CREATE_PAYMENTS', $this->permissionsList);
    }

    public function getSignPaymentsAttribute(): bool
    {
        if (! isset($this->permissionsList)) {
            $this->permissionsList = $this->permissions()->pluck('upname')->toArray();
        }

        return in_array('SIGN_PAYMENTS', $this->permissionsList);
    }

    /**
     * Get relation applicant_individual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class, 'applicant_individual_id', 'id');
    }

    /**
     * Get relation applicant_company
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function ApplicantCompany()
    {
        return $this->belongsTo(ApplicantCompany::class, 'applicant_company_id', 'id');
    }

    /**
     * Get relation members
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function Members()
    {
        return $this->belongsTo(Members::class, 'member_id', 'id');
    }

    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id')->withoutGlobalScope(RoleFilterSuperAdminScope::class);
    }

    public function permissions(): HasManyDeep
    {
        return $this->hasManyDeep(
            Permissions::class,
            [Role::class, 'role_has_permissions'],
            [
                'id',
                'role_id',
                'id',
            ],
            [
                'role_id',
                'id',
                'permission_id',
            ],
        );
    }
}
