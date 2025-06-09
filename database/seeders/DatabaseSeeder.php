<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users first
        User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
        ]);

        // Create additional test users for leaderboard testing
        User::factory(20)->create();

        // Seed the core data in the correct order
        $this->call([
            LevelsSeeder::class,           // Create levels first
            LanguagesSeeder::class,        // Create languages second
            HtmlLessonsSeeder::class,      // Create HTML lessons and questions
            CssLessonsSeeder::class,       // Create CSS lessons and questions
            PythonLessonsSeeder::class,    // Create Python lessons and questions
            UserProgressSeeder::class,     // Create sample user progress (optional)
            QuizResultsSeeder::class,      // Create sample quiz results (optional)
        ]);

        $this->command->info('Database seeded successfully!');
    }
}
