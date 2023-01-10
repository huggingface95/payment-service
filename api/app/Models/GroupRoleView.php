<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class GroupRoleView
 */
class GroupRoleView extends BaseModel
{
    protected $table = 'group_role_view';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function groupType(): BelongsTo
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
    }

    public function modules(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id')->withoutGlobalScope(RoleFilterSuperAdminScope::class);
    }
}
