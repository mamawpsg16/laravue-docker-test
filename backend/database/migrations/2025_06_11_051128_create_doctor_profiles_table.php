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
            $table->string('specialization', 100)->nullable();
            $table->integer('experience_years')->default(0);
            $table->text('qualification')->nullable();
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
           $table->unique('user_id'); // One-to-one relationship
           $table->index('is_available'); // For availability filtering
           $table->index('specialization'); // Optional: for filtering/search
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
