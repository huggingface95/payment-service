<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RespondentFee extends BaseModel
{
    use HasFactory;
    use BaseObServerTrait;

    protected $table = 'respondent_fees';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
