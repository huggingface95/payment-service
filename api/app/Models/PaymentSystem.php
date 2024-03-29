<?php

namespace App\Models;

use App\Models\Interfaces\CustomObServerInterface;
use App\Models\Traits\BaseObServerTrait;
use App\Observers\PaymentSystemObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\EloquentHasManyDeep\HasTableAlias;

/**
 * Class PaymentSystem
 *
 * @property int id
 * @property Company $company
 * @property string name
 * @property int payment_provider_id
 */
class PaymentSystem extends BaseModel implements CustomObServerInterface
{
    use HasRelationships;
    use HasTableAlias;
    use BaseObServerTrait;

    public $timestamps = false;

    protected $table = 'payment_system';

    public const NAME_INTERNAL = 'Internal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active',
        'description',
        'logo_id',
        'payment_provider_id',
    ];

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currencies::class, 'payment_system_currencies', 'payment_system_id', 'currency_id');
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'payment_system_regions', 'payment_system_id', 'region_id');
    }

    public function providers(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(OperationType::class, 'payment_system_operation_types', 'payment_system_id', 'operation_type_id');
    }

    public function companies(): HasManyDeep
    {
        return $this->hasManyDeep(
            Company::class,
            [self::class, PaymentProvider::class],
            [
                'payment_provider_id',
                'id',
                'id',
            ],
            [
                'id',
                'payment_provider_id',
                'company_id',
            ],
        );
    }

    public function company(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->providers(), (new PaymentProvider())->company());
    }

    public function banks(): HasMany
    {
        return $this->hasMany(PaymentBank::class, 'payment_system_id');
    }

    public function bankCorrespondent(): BelongsTo
    {
        return $this->belongsTo(BankCorrespondent::class, 'id', 'payment_system_id')->orderByDesc('id');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'logo_id');
    }

    public function respondentFees(): BelongsToMany
    {
        return $this->belongsToMany(RespondentFee::class, 'payment_system_respondent_fees', 'payment_system_id', 'respondent_fee_id');
    }

    public function commissionTemplate(): HasOneThrough
    {
        return $this->hasOneThrough(CommissionTemplate::class, PaymentProvider::class, 'id', 'payment_provider_id', 'payment_provider_id', 'id');
    }

    public function commissionPriceList(): HasOne
    {
        return $this->hasOne(CommissionPriceList::class, 'payment_system_id', 'id');
    }

    public static function getObServer(): string
    {
        return PaymentSystemObserver::class;
    }
}
