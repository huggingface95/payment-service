<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends BaseModel
{
    use BaseObServerTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'company_id',
    ];

    protected $casts = [
        'deleted_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public $timestamps = false;

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'region_countries', 'region_id', 'country_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function PaymentSystem(): BelongsToMany
    {
        return $this->belongsToMany(PaymentSystem::class, 'payment_system_regions', 'region_id', 'payment_system_id', 'id');
    }
}
