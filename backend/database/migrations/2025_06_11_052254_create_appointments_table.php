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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number', 20)->unique(); // Human-readable ID
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctor_profiles')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained(); // Denormalized for reporting
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show', 'rescheduled'])->default('scheduled');
            $table->enum('priority', ['normal', 'urgent', 'emergency'])->default('normal');
            $table->text('patient_notes')->nullable(); // Patient's notes
            $table->text('doctor_notes')->nullable(); // Doctor's notes
            $table->text('symptoms')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'refunded'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('cancelled_by')->nullable()->constrained('users');
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            
            // Composite indexes for common queries
            $table->index(['patient_id', 'appointment_date', 'status']);
            $table->index(['doctor_id', 'appointment_date', 'status']);
            $table->index(['appointment_date', 'status', 'priority']);
            $table->index(['department_id', 'appointment_date']);
            $table->unique(['doctor_id', 'appointment_date', 'start_time'], 'unique_doctor_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
