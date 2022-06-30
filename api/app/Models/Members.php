<?php

namespace App\Models;

use Ankurk91\Eloquent\MorphToOne;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Traits\UserPermission;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
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
 */
class Members extends BaseModel implements AuthenticatableContract, AuthorizableContract, JWTSubject, CanResetPasswordContract
{
    use SoftDeletes, Authorizable, Authenticatable, UserPermission, HasApiTokens, CanResetPassword, Notifiable, MorphToOne;

    public $password_confirmation;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'sex',
        'is_active',
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
        'groupRoles',
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

    protected $appends = ['two_factor', 'permissions'];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope(parent::getApplicantIdsByAuthMember()));
    }

    public function getTwoFactorAttribute()
    {
        return ($this->google2fa_secret) ? true : false;
    }

    public function getPermissionsAttribute()
    {
        $permissions = collect();

        $permissionsArray = $this->groupRole()
            ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'group_role.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->join('permissions_list', 'permissions_list.id', 'permissions.permission_list_id')
            ->select('permissions.id', 'permissions.display_name', 'permissions.permission_list_id', 'permissions_list.name as permission', 'permissions_list.id as list_id')
            ->get();

        foreach ($permissionsArray as $item) {
            $list[] = $item->permission;
        }
        $lists = array_unique($list);

        foreach ($lists as $listItem) {
            $permission['permission'] = $listItem;
            $actions = [];

            foreach ($permissionsArray as $item) {
                if ($item->permission != $listItem) {
                    continue;
                }
                $actions[] = $item->display_name;
            }
            $permission['actions'] = $actions;
            $permissions[] = $permission;
        }

        return $permissions;
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
        return [];
    }

    public function IsShowOwnerApplicants(): bool
    {
        return $this->is_show_owner_applicants;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
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

    public function getDepartmentAttribute()
    {
        return $this->position()
            ->join('departments', 'departments.id', '=', 'department_position.department_id')->select('departments.*')->first();
    }

    public function twoFactor(): BelongsTo
    {
        return $this->belongsTo(TwoFactorAuthSettings::class, 'two_factor_auth_setting_id');
    }

    public function ipAddress()
    {
        return $this->hasMany(ClientIpAddress::class, 'client_id')->where('client_type', '=', 'App\Models\Members');
    }

    public function roles()
    {
        //TODO add functionality
    }

    public function groupRole(): HasOneThrough
    {
        return $this->hasOneThrough(
            GroupRole::class,
            GroupRoleUser::class,
            'user_id',
            'id',
            'id',
            'group_role_id',
        )->where('group_type_id', GroupRole::MEMBER);
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
        return $this->hasMany(MemberAccessLimitation::class, 'member_id')->with('groupRole');
    }

    public function accountManagerApplicantIndividuals(): HasMany
    {
        return $this->hasMany(ApplicantIndividual::class, 'account_manager_member_id');
    }

    public function accountManagerApplicantCompanies(): HasMany
    {
        return $this->hasMany(ApplicantCompany::class, 'account_manager_member_id');
    }

    public function scopeCompanySort($query, $sort)
    {
        return $query->join('companies', 'companies.id', '=', 'members.company_id')->orderBy('companies.name', $sort)->select('members.*');
    }
}
