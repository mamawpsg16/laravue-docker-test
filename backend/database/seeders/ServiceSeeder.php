<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $services = [
            // Cardiology Services
            ['department_id' => 1, 'name' => 'ECG', 'code' => 'ECG001', 'duration_minutes' => 30, 'base_price' => 150.00],
            ['department_id' => 1, 'name' => 'Echocardiogram', 'code' => 'ECHO001', 'duration_minutes' => 45, 'base_price' => 300.00],
            ['department_id' => 1, 'name' => 'Stress Test', 'code' => 'STRESS001', 'duration_minutes' => 60, 'base_price' => 400.00],
            
            // Neurology Services
            ['department_id' => 2, 'name' => 'EEG', 'code' => 'EEG001', 'duration_minutes' => 60, 'base_price' => 250.00],
            ['department_id' => 2, 'name' => 'MRI Brain', 'code' => 'MRI001', 'duration_minutes' => 90, 'base_price' => 800.00],
            
            // Orthopedics Services
            ['department_id' => 3, 'name' => 'X-Ray', 'code' => 'XRAY001', 'duration_minutes' => 20, 'base_price' => 100.00],
            ['department_id' => 3, 'name' => 'Physical Therapy', 'code' => 'PT001', 'duration_minutes' => 60, 'base_price' => 80.00],
            
            // Emergency Services
            ['department_id' => 4, 'name' => 'Emergency Consultation', 'code' => 'ER001', 'duration_minutes' => 30, 'base_price' => 200.00],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
