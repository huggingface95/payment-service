<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RespondentFee extends BaseModel
{
    use HasFactory;

    protected $table = 'respondent_fees';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
