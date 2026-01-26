<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Get first user or create one

        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $projects = [
            [
                'name' => 'Website Redesign',
                'description' => 'Complete redesign of the company website with modern UI/UX',
                'status' => 'active',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Development of iOS and Android mobile applications',
                'status' => 'active',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Marketing Campaign',
                'description' => 'Q4 marketing campaign planning and execution',
                'status' => 'active',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Database Migration',
                'description' => 'Migration from legacy database to new cloud infrastructure',
                'status' => 'active',
                'created_by' => $user->id,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
