<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'browser_version',
        'os',
        'os_version',
        'country',
        'city',
        'referrer',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
    }
}
