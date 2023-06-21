<?php

namespace App\Models;

use App\Models\Interfaces\CustomObServerInterface;
use App\Models\Traits\BaseObServerTrait;
use App\Observers\TransferFileRelationObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class TransferFIleRelation
 *
 * @property Files $file
 *
 */
class TransferFIleRelation extends Pivot implements CustomObServerInterface
{
    use BaseObServerTrait;

    protected $table = 'transfer_file_relation';

    public $timestamps = false;

    protected $fillable = [
        'transfer_id',
        'transfer_type',
        'file_id',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'file_id');
    }

    public static function getObServer(): string
    {
        return TransferFileRelationObserver::class;
    }
}
