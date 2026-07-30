<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('device_type', 50)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('browser_version', 50)->nullable();
            $table->string('os', 100)->nullable();
            $table->string('os_version', 50)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->timestamp('visited_at')->useCurrent();
            
            $table->index('ip_address');
            $table->index('visited_at');
            $table->index('country');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
