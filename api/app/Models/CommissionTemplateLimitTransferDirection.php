<?php

namespace App\Models;

class CommissionTemplateLimitTransferDirection extends BaseModel
{
    public const ALL = 'All';

    public const INCOMING = 'Incoming';

    public const OUTGOING = 'Outgoing';

    public $timestamps = false;

    protected $table = 'commission_template_limit_transfer_direction';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
