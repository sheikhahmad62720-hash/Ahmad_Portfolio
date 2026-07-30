<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('meeting_url', 500)->nullable();
            $table->dateTime('meeting_date');
            $table->unsignedInteger('duration')->default(60);
            $table->string('status', 50)->default('scheduled');
            $table->timestamps();
            
            $table->index('client_id');
            $table->index('meeting_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
