<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Education extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institution',
        'degree',
        'field_of_study',
        'location',
        'description',
        'start_date',
        'end_date',
        'is_current',
        'grade',
        'order_position',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}
