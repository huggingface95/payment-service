<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [];

    protected $fillable = [
        'file_name', 'mime_type', 'size', 'entity_type', 'author_id', 'storage_path', 'storage_name', 'link'
    ];


}
