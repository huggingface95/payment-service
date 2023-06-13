<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class TransferBetweenAccount
 */
class ApplicantModule extends BaseModel
{
    protected $table = 'applicant_modules_view';

    public function clientable(): MorphTo
    {
        return $this->morphTo('clientable', 'client_type', 'client_id');
    }

}
