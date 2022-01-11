<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Requisites extends Model
{

    protected $table="requisites";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'recipient', 'registration_number', 'address', 'country_id', 'bank_name', 'bank_country_id', 'iban', 'account_no', 'swift', 'bank_correspondent'
    ];


    /**
     * Get relation Country
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }


    /**
     * Get relation applicant Account
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Accounts()
    {
        return $this->belongsTo(Accounts::class,'account_id','id');
    }

}
