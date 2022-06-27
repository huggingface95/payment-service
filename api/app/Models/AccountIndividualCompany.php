<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class AccountIndividualCompany
 *
 * @property ApplicantIndividual|ApplicantCompany client
 */
class AccountIndividualCompany extends BaseModel
{
    protected $table = 'account_individuals_companies';

    protected $fillable = ['account_id', 'client_type', 'client_id'];

    public function client(): MorphTo
    {
        return $this->morphTo();
    }
}
