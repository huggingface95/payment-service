<?php

namespace App\Models;

class Files extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'file_name', 'mime_type', 'size', 'entity_type', 'author_id', 'storage_path', 'storage_name', 'link', 'resolution',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];
}
