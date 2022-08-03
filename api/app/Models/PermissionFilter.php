<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class PermissionQuery
 *
 * @property int id
 * @property string table
 * @property string action
 * @property string column
 * @property string value
 *
 * @method static firstOrCreate(array $array)
 * @method static where(string $string, $action)
 */
class PermissionFilter extends BaseModel
{
    public const SCOPE_MODE = 'scope';

    public const EVENT_MODE = 'event';

    public const EVENT_CREATING = 'creating';

    public const EVENT_SAVING = 'saving';

    public const EVENT_UPDATING = 'updating';

    public const EVENT_DELETING = 'deleting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mode', 'table', 'action', 'column', 'value',
    ];

    public $timestamps = false;

    protected static function booting()
    {
    }

    public static function getModes(): array
    {
        return [
            self::EVENT_MODE,
            self::SCOPE_MODE,
        ];
    }

    public static function getEventActions(): array
    {
        return [
            self::EVENT_CREATING,
            self::EVENT_DELETING,
            self::EVENT_UPDATING,
            self::EVENT_SAVING,
        ];
    }

    public function binds(): BelongsToMany
    {
        return $this->belongsToMany(
            Permissions::class,
            'permission_filters_binds',
            'permission_filters_id',
            'permission_id'
        );
    }
}
