<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_position')->nullable();
            $table->string('client_company')->nullable();
            $table->string('client_avatar', 500)->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('order_position')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('is_featured');
            $table->index('is_published');
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
