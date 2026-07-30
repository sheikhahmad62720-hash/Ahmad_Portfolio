<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->nullable()->constrained()->onDelete('set null');
            $table->string('url', 500);
            $table->string('page_title')->nullable();
            $table->unsignedInteger('view_duration')->nullable();
            $table->timestamp('viewed_at')->useCurrent();
            
            $table->index('url');
            $table->index('viewed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
