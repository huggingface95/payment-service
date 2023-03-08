<?php

namespace App\Models;

use DateTimeInterface;

class Files extends BaseModel
{
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
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d\\TH:i:s.ZZZ\\Z');
    }
}
