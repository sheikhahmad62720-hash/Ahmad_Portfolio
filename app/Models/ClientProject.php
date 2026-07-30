<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientProject extends Model
{
    protected $fillable = [
        'client_id',
        'title',
        'description',
        'status',
        'progress',
        'budget',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'progress' => 'integer',
        'budget' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
