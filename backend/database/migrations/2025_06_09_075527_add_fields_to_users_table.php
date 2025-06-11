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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 100)->after('name');
            $table->string('middle_name', 100)->after('first_name');
            $table->string('last_name', 100)->after('middle_name');
            $table->date('date_of_birth')->nullable()->after('email');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->string('phone')->nullable()->after('gender');
            $table->text('address')->nullable()->after('phone');
            $table->string('emergency_contact_name', 100)->nullable()->after('address');
            $table->string('emergency_contact_phone', 30)->nullable()->after('emergency_contact_name');
            $table->enum('role', ['patient', 'doctor', 'admin', 'receptionist'])->default('patient');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'date_of_birth', 'gender',
                'phone', 'address', 'emergency_contact_name',
                'emergency_contact_phone', 'role', 'is_active', 'last_login_at'
            ]);
        });
    }
};
