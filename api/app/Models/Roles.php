<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $fillable = [
        'name', 'slug','description'
    ];

    public function groups()
    {
        return $this->belongsToMany(Groups::class);
    }
}
