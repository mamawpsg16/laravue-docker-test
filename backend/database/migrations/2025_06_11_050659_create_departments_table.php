<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('code', 10)->unique(); // Department code for easy reference
            $table->text('description')->nullable();
            $table->string('head_doctor_id')->nullable(); // Foreign key added later
            $table->boolean('is_active')->default(true);
            $table->json('operating_hours')->nullable(); // Store flexible hours
            $table->timestamps();
            
            $table->index(['is_active', 'name']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
