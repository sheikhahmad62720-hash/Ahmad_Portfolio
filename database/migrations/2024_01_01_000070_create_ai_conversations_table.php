<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('message');
            $table->text('response');
            $table->string('model_used', 100)->nullable();
            $table->unsignedInteger('tokens_used')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('session_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_conversations');
    }
};
