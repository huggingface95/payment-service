<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class AccountClient
 */
class AccountClient extends BaseModel
{
    protected $table = 'account_clients';

    protected $guard_name = 'api';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'client_type',
    ];

    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    public function individual(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividual::class, 'client_id')->where('client_type', '=', class_basename(ApplicantIndividual::class));
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividual::class, 'client_id')->where('client_type', '=', class_basename(ApplicantCompany::class));
    }

    public function individualGroupRole(): HasOneThrough
    {
        return $this->hasOneThrough(
            GroupRole::class,
            GroupRoleUser::class,
            'user_id',
            'id',
            'client_id',
            'group_role_id'
        )->where('client_type', '=', class_basename(ApplicantIndividual::class))
            ->where('user_type', '=', class_basename(ApplicantIndividual::class));
    }

    public function companyGroupRole(): HasOneThrough
    {
        return $this->hasOneThrough(
            GroupRole::class,
            GroupRoleUser::class,
            'user_id',
            'id',
            'client_id',
            'group_role_id'
        )->where('client_type', '=', class_basename(ApplicantCompany::class))
            ->where('user_type', '=', class_basename(ApplicantCompany::class));
    }
}
