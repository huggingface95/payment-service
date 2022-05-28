<?php

namespace App\Models;

use App\Models\Traits\UserPermission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Collection;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use \Illuminate\Notifications\Notifiable;
/**
 * Class Members
 * @package App\Models
 * @property int id
 * @property bool is_show_owner_applicants
 *
 * @property Collection groupRoles
 * @property GroupRole $groupRole
 * @property EmailSmtp $smtp
 *
 */

class Members extends BaseModel implements AuthenticatableContract, AuthorizableContract, JWTSubject, CanResetPasswordContract
{
    use SoftDeletes, Authorizable, Authenticatable, UserPermission, HasApiTokens, CanResetPassword, Notifiable;

    public $password_confirmation;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'sex', 'is_active', 'company_id', 'country_id', 'language_id', 'two_factor_auth_setting_id', 'password_hash', 'password_salt', 'last_login_at', 'additional_fields', 'additional_info_fields', 'is_show_owner_applicants'
    ];

    protected $hidden = [
        'password_hash',
        'password_salt',
        'google2fa_secret',
        'security_pin'
    ];

    protected $casts = [
        'backup_codes' => 'array',
    ];



    protected $dates = ['deleted_at'];

    protected $appends = array('two_factor');


    public function getTwoFactorAttribute()
    {
        return ($this->google2fa_secret) ? true : false;
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

    public function groupRoles(): BelongsToMany
    {
        return $this->belongsToMany(
            GroupRole::class,
            'group_role_members_individuals',
            'user_id',
            'group_role_id'
        )->where('group_type_id', GroupRole::MEMBER);
    }

    public function smtp(): HasOne
    {
        return $this->hasOne(EmailSmtp::class, 'member_id');
    }

    public function emailTemplates(): HasMany
    {
        return $this->hasMany(EmailTemplate::class, 'member_id');
    }

}
