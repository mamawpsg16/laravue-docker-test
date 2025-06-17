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
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number', 50)->unique();
            $table->json('specializations'); // Multiple specializations
            $table->integer('experience_years')->default(0);
            $table->json('qualifications')->nullable(); // Store multiple qualifications
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->text('about')->nullable(); // Doctor's bio
            $table->json('languages_spoken')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('accepts_emergency')->default(false);
            $table->timestamps();
            
            $table->unique('user_id');
            $table->index(['is_available', 'accepts_emergency']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};
