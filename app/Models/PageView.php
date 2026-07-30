<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'visitor_id',
        'url',
        'page_title',
        'view_duration',
        'viewed_at',
    ];

    protected $casts = [
        'view_duration' => 'integer',
        'viewed_at' => 'datetime',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }
}
