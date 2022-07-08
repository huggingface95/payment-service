<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\MorphTo;


/**
 * Class AccountClient
 *
 */
class AccountClient extends BaseModel
{

    protected $table = 'account_clients';

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'client_type'
    ];


    public function client(): MorphTo
    {
        return $this->morphTo();
    }


}
