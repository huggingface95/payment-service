<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Files
 *
 * @property Members|ApplicantIndividual|ApplicantCompany|null $user

 */
class Files extends BaseModel
{
    use BaseObServerTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'file_name',
        'mime_type',
        'size',
        'entity_type',
        'author_id',
        'storage_path',
        'storage_name',
        'link',
        'resolution',
        'user_id',
        'user_type',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d\\TH:i:s.ZZZ\\Z');
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
