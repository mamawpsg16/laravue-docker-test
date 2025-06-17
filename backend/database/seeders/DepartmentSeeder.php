<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $departments = [
            [
                'name' => 'Cardiology',
                'code' => 'CARD',
                'description' => 'Heart and cardiovascular system treatment',
                'operating_hours' => [
                    'monday' => ['start' => '08:00', 'end' => '17:00'],
                    'tuesday' => ['start' => '08:00', 'end' => '17:00'],
                    'wednesday' => ['start' => '08:00', 'end' => '17:00'],
                    'thursday' => ['start' => '08:00', 'end' => '17:00'],
                    'friday' => ['start' => '08:00', 'end' => '17:00'],
                    'saturday' => ['start' => '08:00', 'end' => '12:00'],
                ],
            ],
            [
                'name' => 'Neurology',
                'code' => 'NEURO',
                'description' => 'Brain and nervous system treatment',
                'operating_hours' => [
                    'monday' => ['start' => '09:00', 'end' => '16:00'],
                    'tuesday' => ['start' => '09:00', 'end' => '16:00'],
                    'wednesday' => ['start' => '09:00', 'end' => '16:00'],
                    'thursday' => ['start' => '09:00', 'end' => '16:00'],
                    'friday' => ['start' => '09:00', 'end' => '16:00'],
                ],
            ],
            [
                'name' => 'Orthopedics',
                'code' => 'ORTHO',
                'description' => 'Bone, joint, and muscle treatment',
                'operating_hours' => [
                    'monday' => ['start' => '07:00', 'end' => '18:00'],
                    'tuesday' => ['start' => '07:00', 'end' => '18:00'],
                    'wednesday' => ['start' => '07:00', 'end' => '18:00'],
                    'thursday' => ['start' => '07:00', 'end' => '18:00'],
                    'friday' => ['start' => '07:00', 'end' => '18:00'],
                    'saturday' => ['start' => '08:00', 'end' => '14:00'],
                ],
            ],
            [
                'name' => 'Emergency',
                'code' => 'ER',
                'description' => '24/7 Emergency medical services',
                'operating_hours' => [
                    'monday' => ['start' => '00:00', 'end' => '23:59'],
                    'tuesday' => ['start' => '00:00', 'end' => '23:59'],
                    'wednesday' => ['start' => '00:00', 'end' => '23:59'],
                    'thursday' => ['start' => '00:00', 'end' => '23:59'],
                    'friday' => ['start' => '00:00', 'end' => '23:59'],
                    'saturday' => ['start' => '00:00', 'end' => '23:59'],
                    'sunday' => ['start' => '00:00', 'end' => '23:59'],
                ],
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
