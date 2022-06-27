<?php

namespace App\Models;

class CompanySettings extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_url', 'email_jwt', 'email_from', 'logo_object_key', 'show_own_logo',
    ];

    public $primaryKey = 'company_id';

    public $timestamps = false;

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Companies', 'company_id');
    }
}
