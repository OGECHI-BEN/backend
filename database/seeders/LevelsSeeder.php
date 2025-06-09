<?php
// database/seeders/LevelsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelsSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            [
                'name' => 'Beginner',
                'slug' => 'beginner',
                'description' => 'Perfect for those starting their programming journey. Learn the fundamentals and basic concepts.',
                'order' => 1,
                'color' => '#10b981', // Green
            ],
            [
                'name' => 'Intermediate',
                'slug' => 'intermediate',
                'description' => 'For those with basic understanding looking to advance their skills and tackle more complex topics.',
                'order' => 2,
                'color' => '#f59e0b', // Yellow
            ],
            [
                'name' => 'Expert',
                'slug' => 'expert',
                'description' => 'Advanced concepts and real-world applications for experienced developers.',
                'order' => 3,
                'color' => '#ef4444', // Red
            ]
        ];

        foreach ($levels as $level) {
            Level::firstOrCreate(
                ['slug' => $level['slug']],
                $level
            );
        }

        $this->command->info('Levels seeded successfully!');
    }
}