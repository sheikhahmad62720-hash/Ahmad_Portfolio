<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIPrompt extends Model
{
    protected $table = 'ai_prompts';

    protected $fillable = [
        'name',
        'slug',
        'category',
        'prompt',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
