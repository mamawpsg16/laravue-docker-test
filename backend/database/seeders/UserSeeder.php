<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DoctorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create Admin User
        $admin = User::create([
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'is_active' => true,
        ]);

        // Create Sample Doctors
        $doctors = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'dr.smith@hospital.com',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => '+1234567891',
                'profile' => [
                    'license_number' => 'MD12345',
                    'specializations' => ['Cardiology', 'Internal Medicine'],
                    'experience_years' => 15,
                    'consultation_fee' => 200.00,
                    'qualifications' => ['MD - Harvard Medical School', 'Fellowship in Cardiology'],
                    'is_available' => true,
                ]
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'dr.johnson@hospital.com',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => '+1234567892',
                'profile' => [
                    'license_number' => 'MD12346',
                    'specializations' => ['Neurology'],
                    'experience_years' => 12,
                    'consultation_fee' => 250.00,
                    'qualifications' => ['MD - Johns Hopkins', 'Neurology Residency'],
                    'is_available' => true,
                ]
            ],
        ];

        foreach ($doctors as $doctorData) {
            $profile = $doctorData['profile'];
            unset($doctorData['profile']);
            
            $doctor = User::create($doctorData);
            $profile['user_id'] = $doctor->id;
        DoctorProfile::create($profile);
        }

        // Create Sample Patients
        User::factory(50)->create(['role' => 'patient']);
    }
}
