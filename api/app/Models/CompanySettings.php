<?php

namespace App\Models;


/**
 * Class CompanySettings
 *
 * @property int company_id
 * @property string vv_token
 *
 */
class CompanySettings extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_url', 'email_jwt', 'email_from', 'logo_object_key', 'show_own_logo', 'vv_token', 'company_id'
    ];

    public $primaryKey = 'id';

    public $timestamps = false;

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Companies', 'company_id');
    }
}
