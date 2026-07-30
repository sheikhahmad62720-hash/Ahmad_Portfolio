<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_type', 100);
            $table->string('page_identifier')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('twitter_card', 50)->default('summary_large_image');
            $table->json('schema_markup')->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->string('robots', 100)->default('index,follow');
            $table->timestamps();
            
            $table->unique(['page_type', 'page_identifier']);
            $table->index('page_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
