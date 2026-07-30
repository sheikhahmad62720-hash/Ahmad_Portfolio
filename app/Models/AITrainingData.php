<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AITrainingData extends Model
{
    protected $table = 'ai_training_data';

    protected $fillable = [
        'data_type',
        'content',
        'embedding',
        'metadata',
        'is_active',
    ];

    protected $casts = [
        'embedding' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
