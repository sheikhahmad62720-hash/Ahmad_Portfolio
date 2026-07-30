<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('issuer');
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('credential_id')->nullable();
            $table->string('credential_url', 500)->nullable();
            $table->string('certificate_file', 500)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->unsignedInteger('order_position')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('issue_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
