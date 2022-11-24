<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'client_url',
        'email_jwt',
        'email_from',
        'logo_id',
        'show_own_logo',
        'vv_token',
        'company_id',
        'vv_token',
        'member_verify_url',
        'backoffice_login_url',
        'backoffice_forgot_password_url',
        'backoffice_support_url',
        'backoffice_support_email',
    ];


    public $timestamps = false;

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Companies', 'company_id');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'logo_id');
    }
}
