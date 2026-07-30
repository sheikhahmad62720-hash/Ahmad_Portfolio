<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = [
        'service_name',
        'api_key',
        'api_secret',
        'additional_config',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'additional_config' => 'array',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public static function getKey(string $serviceName): ?string
    {
        $apiKey = static::where('service_name', $serviceName)
            ->where('is_active', true)
            ->first();

        return $apiKey && !$apiKey->isExpired() ? decrypt($apiKey->api_key) : null;
    }
}
