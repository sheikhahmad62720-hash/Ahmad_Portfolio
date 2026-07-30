<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $fillable = [
        'client_id',
        'title',
        'description',
        'meeting_url',
        'meeting_date',
        'duration',
        'status',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'duration' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function isUpcoming(): bool
    {
        return $this->meeting_date->isFuture();
    }
}
