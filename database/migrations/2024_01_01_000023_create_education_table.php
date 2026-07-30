<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('institution');
            $table->string('degree');
            $table->string('field_of_study')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->string('grade', 50)->nullable();
            $table->unsignedInteger('order_position')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('is_current');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
