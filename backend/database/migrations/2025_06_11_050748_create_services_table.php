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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('code', 20)->unique(); // Service code
            $table->text('description')->nullable();
            $table->integer('duration_minutes'); // More explicit naming
            $table->decimal('base_price', 10, 2)->default(0);
            $table->text('preparation_instructions')->nullable();
            $table->boolean('requires_fasting')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['department_id', 'is_active']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
