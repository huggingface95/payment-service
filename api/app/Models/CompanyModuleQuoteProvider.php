<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompanyModuleQuoteProvider extends BaseModel
{

    use BaseObServerTrait;

    public $timestamps = false;

    protected $fillable = [
        'company_module_id',
        'quote_provider_id',
        'is_active',
    ];

    public function quoteProvider(): BelongsTo
    {
        return $this->belongsTo(QuoteProvider::class, 'quote_provider_id');
    }

    public function projectApiSettings(): BelongsToMany
    {
        return $this->belongsToMany(
            ProjectApiSetting::class,
            ProjectSettings::class,
            'quote_provider_id',
            'project_id',
            'quote_provider_id',
            'project_id'
        );
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            ProjectSettings::class,
            'quote_provider_id',
            'project_id',
            'quote_provider_id',
            'id'
        );
    }
}
