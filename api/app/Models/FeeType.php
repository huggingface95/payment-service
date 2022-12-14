<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class FeeType extends BaseModel
{
    protected $table = 'fee_types';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public const FEES = 'Fees';

    public const SERVICE_FEE = 'Service fee';

    public function operationType(): hasOne
    {
        return $this->hasOne(OperationType::class, 'fee_type_id', 'id');
    }
}
