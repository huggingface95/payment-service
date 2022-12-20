<?php

namespace App\Models;

use Ankurk91\Eloquent\MorphToOne;
use App\Enums\MemberStatusEnum;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Traits\UserPermission;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Members
 *
 * @property int id
 * @property bool is_show_owner_applicants
 * @property string email
 * @property string first_name
 * @property string last_name
 * @property string fullname
 * @property string sex
 * @property int company_id
 * @property int country_id
 * @property int language_id
 * @property int two_factor_auth_setting_id
 * @property string google2fa_secret
 * @property string backup_codes
 * @property Collection groupRoles
 * @property GroupRole $groupRole
 * @property EmailSmtp $smtp
 * @property bool is_super_admin
 * @property Role role
 *
 * @method
 */
class Members extends BaseModel implements AuthenticatableContract, AuthorizableContract, JWTSubject, CanResetPasswordContract
{
    use SoftDeletes;
    use Authorizable;
    use Authenticatable;
    use UserPermission;
    use HasApiTokens;
    use CanResetPassword;
    use Notifiable;
    use MorphToOne;
    use HasRelationships;

    public $password_confirmation;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'sex',
        'company_id',
        'country_id',
        'language_id',
        'two_factor_auth_setting_id',
        'password_hash',
        'password_salt',
        'last_login_at',
        'additional_fields',
        'additional_info_fields',
        'is_show_owner_applicants',
        'is_sign_transaction',
        'email_veirfication',
        'groupRoles',
        'department_position_id',
        'is_need_change_password',
        'member_status_id',
        'entity_id',
    ];

    protected $hidden = [
        'password_hash',
        'password_salt',
        'google2fa_secret',
        'security_pin',
    ];

    protected $casts = [
        'backup_codes' => 'array',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = ['two_factor', 'permissions', 'is_super_admin', 'is_active'];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope);
    }

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getTwoFactorAttribute()
    {
        return ($this->google2fa_secret) ? true : false;
    }

    public function getPermissionsAttribute(): array
    {
        return $this->getAllPermissions()->groupBy(['permission_list_id', function ($permission) {
            return $permission->permissionList->name;
        }])->collapse()->mapWithKeys(function ($permissions, $list) {
            return [strtoupper(Str::snake(str_replace(':', '', $list))) => $permissions->pluck('id')->toArray()];
        })->toArray();
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'client_type' => 'member',
        ];
    }

    public function IsShowOwnerApplicants(): bool
    {
        return $this->is_show_owner_applicants;
    }

    public function getIsSuperAdminAttribute(): bool
    {
        try {
            return $this->groupRole->role->IsSuperAdmin();
        } catch (\Throwable) {
            return false;
        }
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->member_status_id == MemberStatusEnum::ACTIVE->value;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Languages::class, 'language_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(DepartmentPosition::class, 'department_position_id');
    }

    public function memberStatus(): BelongsTo
    {
        return $this->belongsTo(MemberStatus::class, 'member_status_id');
    }

    public function department(): HasOneThrough
    {
        return $this->hasOneThrough(
            Department::class,
            DepartmentPositionRelation::class,
            'position_id',
            'id',
            'department_position_id',
            'department_id'
        );
    }

    public function twoFactor(): BelongsTo
    {
        return $this->belongsTo(TwoFactorAuthSettings::class, 'two_factor_auth_setting_id');
    }

    public function ipAddress()
    {
        return $this->hasMany(ClientIpAddress::class, 'client_id')->where('client_type', '=', class_basename(Members::class));
    }

    public function roles()
    {
        //TODO add functionality
    }

    public function groupRole(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphToOne(GroupRole::class, 'user', GroupRoleUser::class, 'user_id', 'group_role_id');
    }

    public function role(): HasOneDeep
    {
        return $this->hasOneDeep(
            Role::class,
            [GroupRoleUser::class, GroupRole::class],
            [
                'user_id',
                'id',
                'id',
            ],
            [
                'id',
                'group_role_id',
                'role_id',
            ]
        )->where('user_type', class_basename(self::class));
    }

    public function groupRoles(): MorphToMany
    {
        return $this->morphToMany(GroupRole::class, 'user', GroupRoleUser::class, 'user_id', 'group_role_id');
    }

    public function smtp(): HasOne
    {
        return $this->hasOne(EmailSmtp::class, 'member_id');
    }

    public function emailTemplates(): HasMany
    {
        return $this->hasMany(EmailTemplate::class, 'member_id');
    }

    public function accessLimitations(): HasMany
    {
        return $this->hasMany(MemberAccessLimitation::class, 'member_id');
    }

    public function accountManagerApplicantIndividuals(): HasMany
    {
        return $this->hasMany(ApplicantIndividual::class, 'account_manager_member_id');
    }

    public function accountManagerApplicantCompanies(): HasMany
    {
        return $this->hasMany(ApplicantCompany::class, 'account_manager_member_id');
    }

    public function accountManagerMembers(): HasMany
    {
        return $this->hasMany(self::class, 'company_id', 'company_id');
    }

    public function scopeCompanySort($query, $sort)
    {
        return $query->join('companies', 'companies.id', '=', 'members.company_id')->orderBy('companies.name', $sort)->select('members.*');
    }

    public function scopeGetGroup(Builder $query, $groupId)
    {
        return $query->join('group_role_members_individuals', 'members.id', '=', 'group_role_members_individuals.user_id')
            ->join('group_role', 'group_role_members_individuals.group_role_id', '=', 'group_role.id')
            ->where('group_role_members_individuals.user_type', '=', self::class)
            ->where('group_role.id', '=', $groupId)
            ->select('members.*');
    }
}
