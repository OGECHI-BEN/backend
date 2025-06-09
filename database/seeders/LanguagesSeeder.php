<?php
// database/seeders/LanguagesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguagesSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            [
                'name' => 'HTML',
                'slug' => 'html',
                'description' => 'HyperText Markup Language - The foundation of web pages and web applications.',
                'icon' => 'html5-icon.svg',
                'color' => '#e34c26',
                'is_active' => true,
                'order' => 1
            ],
            [
                'name' => 'CSS',
                'slug' => 'css',
                'description' => 'Cascading Style Sheets - Style and layout language for designing beautiful web pages.',
                'icon' => 'css3-icon.svg',
                'color' => '#1572b6',
                'is_active' => true,
                'order' => 2
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'description' => 'A versatile, high-level programming language perfect for beginners and powerful for experts.',
                'icon' => 'python-icon.svg',
                'color' => '#3776ab',
                'is_active' => true,
                'order' => 3
            ]
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(
                ['slug' => $language['slug']],
                $language
            );
        }

        $this->command->info('Languages seeded successfully!');
    }
}