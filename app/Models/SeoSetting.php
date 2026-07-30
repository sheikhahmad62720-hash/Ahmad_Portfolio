<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'page_type',
        'page_identifier',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card',
        'schema_markup',
        'canonical_url',
        'robots',
    ];

    protected $casts = [
        'schema_markup' => 'array',
    ];

    public static function getForPage(string $pageType, ?string $identifier = null): ?self
    {
        return static::where('page_type', $pageType)
            ->where('page_identifier', $identifier)
            ->first();
    }
}
