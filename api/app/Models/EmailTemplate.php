<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;


/**
 * Class EmailTemplate
 * @package App\Models
 * @property int id
 * @property string subject
 * @property string content
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class EmailTemplate extends BaseModel
{
    const SUCCESS = 'success';
    const ERROR = 'error';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'content', 'type', 'header', 'footer'
    ];




}
