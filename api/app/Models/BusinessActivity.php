<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BusinessActivity extends BaseModel
{
    public $timestamps = false;

    protected $table = 'business_activity';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function commissionTemplate(): BelongsToMany
    {
        return $this->belongsToMany(CommissionTemplate::class, 'commission_template_business_activity', 'business_activity_id', 'commission_template_id');
    }
}
