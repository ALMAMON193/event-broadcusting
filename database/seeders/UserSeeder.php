<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\Patient;
use App\Models\PatientMember;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // প্রথমে ইউজার তৈরি করি
        $users = [
            [
                'name' => 'Dr. John Doe',
                'email' => 'admin@admin.com',
                'password' => Hash::make('123456'),
                'is_admin' => true,
                'role' => 'admin',
            ],
            [
                'name' => 'Doctor Alice',
                'email' => 'doctor@gmail.com',
                'password' => Hash::make('123456'),
                'is_admin' => false,
                'role' => 'doctor',
            ],
            [
                'name' => 'Patient Bob',
                'email' => 'patient@gmail.com',
                'password' => Hash::make('123456'),
                'is_admin' => false,
                'role' => 'patient',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // এখন ডাটাগুলো মডেল থেকে রিলেশন অনুসারে নিয়ে আসি
        $doctorUser = User::where('email', 'doctor@gmail.com')->first();
        $patientUser = User::where('email', 'patient@gmail.com')->first();

        // DoctorProfile তৈরি
        if ($doctorUser) {
            DoctorProfile::updateOrCreate(
                ['user_id' => $doctorUser->id],
                [
                    'name' => $doctorUser->name,
                    'email' => $doctorUser->email,
                    'phone' => '01711111111',
                    'specialization' => 'Cardiology',
                    'bio' => 'Experienced heart specialist.',
                ]
            );
        }

        // Patient তৈরি
        if ($patientUser) {
            $patient = Patient::updateOrCreate(
                ['user_id' => $patientUser->id],
                [
                    'name' => $patientUser->name,
                    'email' => $patientUser->email,
                    'phone' => '01822222222',
                ]
            );

            // PatientMember তৈরি
            PatientMember::updateOrCreate(
                ['patient_id' => $patient->id, 'name' => 'Father of Bob'],
                [
                    'relationship' => 'father',
                ]
            );

            PatientMember::updateOrCreate(
                ['patient_id' => $patient->id, 'name' => 'Mother of Bob'],
                [
                    'relationship' => 'mother',
                ]
            );
        }
    }
}
