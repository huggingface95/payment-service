<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    protected $fillable = [
        'first_name', 'last_name','email','sex','is_active','password_hash','password_salt'
    ];
}
