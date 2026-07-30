<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIConversation extends Model
{
    public $timestamps = false;

    protected $table = 'ai_conversations';

    protected $fillable = [
        'session_id',
        'user_id',
        'message',
        'response',
        'model_used',
        'tokens_used',
    ];

    protected $casts = [
        'tokens_used' => 'integer',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
