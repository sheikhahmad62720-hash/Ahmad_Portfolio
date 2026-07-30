<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'image_url',
        'caption',
        'order_position',
    ];

    protected $casts = [
        'order_position' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
