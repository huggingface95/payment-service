<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{

    public $timestamps = false;

    protected $casts = [
        'meta' => 'array',
        'performed_at' => 'datetime'
    ];

    protected $guarded = [];

    protected $hidden = [];


    protected $table = 'model_histories';

    public function user()
    {
        return $this->hasUser() ? $this->morphTo()->first() : null;
    }

    public function hasUser()
    {
        return !empty($this->user_type) && !empty($this->user_id);
    }

    public function model()
    {
        return $this->morphTo()->first();
    }
}
