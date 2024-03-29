<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\RoleFilterSuperAdminScope;
use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Class GroupRole
 *
 * @property Role $role
 * @property Collection users
 */
class GroupRole extends BaseModel
{
    use SoftDeletes;
    use BaseObServerTrait;

    public const INDIVIDUAL = '3';

    public const COMPANY = '2';

    public const MEMBER = '1';

    public $timestamps = false;

    protected $table = 'group_role';

    protected $fillable = [
        'name',
        'group_type_id',
        'role_id',
        'is_active',
        'description',
        'company_id',
        'module_id',
        'entity_id',
    ];

    protected $casts = [
        'deleted_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public static self $clone;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }

    public function load($relations): self
    {
        self::$clone = $this->replicate();

        return parent::load($relations);
    }

    public function groupType(): BelongsTo
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
    }

    public function groupRoleProviders(): HasMany
    {
        return $this->hasMany(GroupRoleProvider::class, 'group_role_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id')->withoutGlobalScope(RoleFilterSuperAdminScope::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function individuals(): MorphToMany
    {
        return $this->morphedByMany(ApplicantIndividual::class, 'user', GroupRoleUser::class, 'group_role_id');
    }

    public function companies(): MorphToMany
    {
        return $this->morphedByMany(ApplicantCompany::class, 'user', GroupRoleUser::class, 'group_role_id');
    }

    public function members(): MorphToMany
    {
        return $this->morphedByMany(Members::class, 'user', GroupRoleUser::class, 'group_role_id');
    }

    public function modules(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function users(): BelongsToMany
    {
        /** @var GroupRole $model */
        try {
            $model = self::$clone;
        } catch (\Error $ex) {
            $model = $this;
        }

        if ($model->group_type_id == self::INDIVIDUAL) {
            return $this->individuals();
        } elseif ($model->group_type_id == self::COMPANY) {
            return $this->companies();
        } else {
            return $this->members();
        }
    }

    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(
            Project::class,
            ProjectSettings::class,
            'group_role_id',
            'id',
            'id',
            'project_id'
        );
    }
}
