<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@zahirtech.com'],
            [
                'name' => 'Admin User',
                'role' => 'admin',
                'password' => Hash::make('ThinkBig@2026'), // Change password if needed
                'email_verified_at' => now(),
            ]
        );

        // Create Superadmin
        $user = User::updateOrCreate(
            ['email' => 'superadmin@zahirtech.com'],
            [
                'name' => 'Super Admin User',
                'role' => 'superadmin',
                'password' => Hash::make('ThinkBig@2026'), // Change password if needed
                'email_verified_at' => now(),
            ]
        );

        $projects = [
            [
                'name' => 'Zahir Tech',
                'description' => 'ZahirTech is a leading technology solutions provider specializing in web development, mobile apps, cloud solutions, and AI-powered systems. We help businesses transform digitally with innovative technology solutions.',
                'status' => 'active',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Zahir Food',
                'description' => 'All-in-one multi-restaurant management platform: Admins can centrally manage restaurants, menus, customers, delivery staff, and promotions. Customers browse nearby eateries, order food, or register as vendors or delivery partners.',
                'status' => 'active',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Door Delivery',
                'description' => 'All-in-one multi-restaurant management platform: Admins can centrally manage restaurants, menus, customers, delivery staff, and promotions. Customers browse nearby eateries, order food, or register as vendors or delivery partners.',
                'status' => 'active',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Doo Attend',
                'description' => 'All-in-one app for modern teams. Uses facial recognition to record employee attendance, track check-ins/check-outs, assign and monitor tasks, manage schedules, and handle leave requests with real-time progress tracking.',
                'status' => 'active',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Doo POS',
                'description' => 'Powerful cloud-based point-of-sale system built for restaurants and retail businesses. Streamlines sales, billing, and payments while helping you track inventory, manage staff, and engage with customers in real time.',
                'status' => 'active',
                'created_by' => $user->id,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
