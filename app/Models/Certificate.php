<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'issuer',
        'issue_date',
        'expiry_date',
        'credential_id',
        'credential_url',
        'certificate_file',
        'description',
        'is_verified',
        'order_position',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_verified' => 'boolean',
    ];

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
