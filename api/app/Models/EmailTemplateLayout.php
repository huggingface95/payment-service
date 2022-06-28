<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class EmailTemplate
 *
 * @property int id
 * @property int email_template_id
 * @property string header
 * @property string footer
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Companies $company
 */
class EmailTemplateLayout extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'header', 'footer', 'company_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }
}
