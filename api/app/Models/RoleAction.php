<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RoleAction
 *
 * @property int id
 * @property int role_id
 * @property string table
 * @property string action
 */
class RoleAction extends BaseModel
{
    public const ACTION_CREATING = 'creating';

    public const ACTION_SAVING = 'saving';

    public const ACTION_UPDATING = 'updating';

    public const ACTION_DELETING = 'deleting';

    protected $fillable = [
        'role_id', 'table', 'action',
    ];

    public $timestamps = false;

    public static function getActions(): array
    {
        return [
            self::ACTION_CREATING,
            self::ACTION_SAVING,
            self::ACTION_UPDATING,
            self::ACTION_DELETING,
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
