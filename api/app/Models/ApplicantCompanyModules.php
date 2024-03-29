<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\ApplicantIndividualCompanyIdScope;
use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ApplicantCompanyModules extends Pivot
{
    use BaseObServerTrait;

    protected $table = 'applicant_company_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_company_id', 'module_id', 'is_active',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope);
        static::addGlobalScope(new ApplicantIndividualCompanyIdScope);
    }

    /**
     * Get relation applicant_company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantCompany()
    {
        return $this->belongsTo(ApplicantCompany::class, 'applicant_company_id', 'id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
