<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApplicantIndividualModules extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'applicant_individual_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id',
        'module_id',
        'is_active',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope);
    }

    public function ApplicantIndividual(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantIndividual::class, 'applicant_individual_modules', 'module_id', 'applicant_individual_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
