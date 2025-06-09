<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Language;
use App\Models\Level;
use App\Models\Question;
use App\Models\Exercise;

class HtmlLessonsSeeder extends Seeder
{
    public function run()
    {
        // Get HTML language and levels
        $html = Language::where('slug', 'html')->first();
        $beginner = Level::where('slug', 'beginner')->first();
        $intermediate = Level::where('slug', 'intermediate')->first();
        $expert = Level::where('slug', 'expert')->first();

        // Check if required data exists
        if (!$html) {
            $this->command->error('HTML language not found. Please run LanguagesSeeder first.');
            return;
        }

        if (!$beginner || !$intermediate || !$expert) {
            $this->command->error('Required levels not found. Please run LevelsSeeder first.');
            return;
        }

        // Beginner Level Lessons
        $introLesson = $this->createLesson([
            'title' => 'Introduction to HTML',
            'slug' => 'introduction',
            'content' => json_encode([
                'sections' => [
                    [
                        'title' => 'What is HTML?',
                        'content' => 'HTML (HyperText Markup Language) is the standard markup language for creating web pages. It describes the structure of a web page and consists of a series of elements.',
                        'codeExample' => '<!DOCTYPE html>
<html>
<head>
    <title>My First HTML Page</title>
</head>
<body>
    <h1>Hello, World!</h1>
    <p>This is my first HTML page.</p>
</body>
</html>'
                    ],
                    [
                        'title' => 'Basic Structure',
                        'items' => [
                            [
                                'code' => '<!DOCTYPE html>',
                                'description' => 'Declares the document type and HTML version'
                            ],
                            [
                                'code' => '<html>',
                                'description' => 'The root element of an HTML page'
                            ],
                            [
                                'code' => '<head>',
                                'description' => 'Contains meta information about the document'
                            ],
                            [
                                'code' => '<body>',
                                'description' => 'Contains the visible page content'
                            ]
                        ]
                    ]
                ],
                'exercise' => [
                    'description' => 'Create your first HTML page with a title and a paragraph.',
                    'requirements' => [
                        'Add a proper DOCTYPE declaration',
                        'Include the basic HTML structure',
                        'Add a title in the head section',
                        'Add a heading and a paragraph in the body'
                    ]
                ],
                'navigation' => [
                    'next' => 'elements-and-tags',
                    'previous' => null
                ]
            ]),
            'language_id' => $html->id,
            'level_id' => $beginner->id,
            'order' => 1,
            'estimated_time' => 15,
            'is_published' => true
        ]);

        $elementsLesson = $this->createLesson([
            'title' => 'HTML Elements and Tags',
            'slug' => 'elements-and-tags',
            'content' => json_encode([
                'sections' => [
                    [
                        'title' => 'Introduction to HTML Elements',
                        'content' => 'HTML elements are the building blocks of HTML pages. They are represented by tags and can contain content, other elements, or both.'
                    ],
                    [
                        'title' => 'Common HTML Elements',
                        'items' => [
                            [
                                'code' => '<h1> to <h6>',
                                'description' => 'Headings of different levels'
                            ],
                            [
                                'code' => '<p>',
                                'description' => 'Paragraphs of text'
                            ],
                            [
                                'code' => '<a>',
                                'description' => 'Hyperlinks'
                            ],
                            [
                                'code' => '<img>',
                                'description' => 'Images'
                            ]
                        ]
                    ]
                ],
                'exercise' => [
                    'description' => 'Create a simple HTML page with various elements.',
                    'requirements' => [
                        'Add a main heading',
                        'Include two paragraphs',
                        'Add a link to your favorite website',
                        'Insert an image'
                    ]
                ],
                'navigation' => [
                    'next' => 'text-formatting',
                    'previous' => 'introduction'
                ]
            ]),
            'language_id' => $html->id,
            'level_id' => $beginner->id,
            'order' => 2,
            'estimated_time' => 20,
            'is_published' => true
        ]);

        // Add questions for each lesson
        $this->addQuestions($introLesson, [
            [
                'type' => 'multiple-choice',
                'question' => 'What does HTML stand for?',
                'options' => [
                    'HyperText Markup Language',
                    'High Tech Modern Language',
                    'Hyper Transfer Markup Language',
                    'HyperText Modern Language'
                ],
                'correct_answer' => 'HyperText Markup Language',
                'points' => 1
            ],
            [
                'type' => 'multiple-choice',
                'question' => 'Which tag is used to create a paragraph?',
                'options' => [
                    '<paragraph>',
                    '<p>',
                    '<text>',
                    '<para>'
                ],
                'correct_answer' => '<p>',
                'points' => 1
            ],
            [
                'type' => 'code',
                'question' => 'Write the basic structure of an HTML document.',
                'correct_answer' => '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n</body>\n</html>',
                'points' => 2
            ]
        ]);

        $this->addQuestions($elementsLesson, [
            [
                'type' => 'multiple-choice',
                'question' => 'Which tag is used for the largest heading?',
                'options' => [
                    '<h1>',
                    '<heading>',
                    '<head>',
                    '<h6>'
                ],
                'correct_answer' => '<h1>',
                'points' => 1
            ],
            [
                'type' => 'multiple-choice',
                'question' => 'What is the correct HTML for creating a hyperlink?',
                'options' => [
                    '<a href="url">Link text</a>',
                    '<link>url</link>',
                    '<a>url</a>',
                    '<url>link</url>'
                ],
                'correct_answer' => '<a href="url">Link text</a>',
                'points' => 1
            ],
            [
                'type' => 'code',
                'question' => 'Create an image tag that displays an image named "logo.png" with alt text "Company Logo".',
                'correct_answer' => '<img src="logo.png" alt="Company Logo">',
                'points' => 2
            ]
        ]);

        // Add exercises for each lesson
        $this->addExercises($introLesson, [
            [
                'title' => 'Your First HTML Page',
                'description' => 'Create a basic HTML page with proper structure',
                'instructions' => 'Create an HTML page that includes a title, heading, and paragraph',
                'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n    <title></title>\n</head>\n<body>\n\n</body>\n</html>',
                'points' => 5,
                'difficulty' => 'beginner',
                'order' => 1
            ],
            [
                'title' => 'HTML Structure Quiz',
                'description' => 'Test your knowledge of HTML document structure',
                'instructions' => 'Identify the correct order of HTML elements',
                'points' => 3,
                'difficulty' => 'beginner',
                'order' => 2
            ]
        ]);

        $this->addExercises($elementsLesson, [
            [
                'title' => 'Element Playground',
                'description' => 'Practice using different HTML elements',
                'instructions' => 'Create a page using various HTML elements',
                'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n    <title>Element Playground</title>\n</head>\n<body>\n\n</body>\n</html>',
                'points' => 5,
                'difficulty' => 'beginner',
                'order' => 1
            ],
            [
                'title' => 'Element Quiz',
                'description' => 'Test your knowledge of HTML elements',
                'instructions' => 'Identify the correct usage of HTML elements',
                'points' => 3,
                'difficulty' => 'beginner',
                'order' => 2
            ]
        ]);

        // Add more lessons for beginner level
        $textFormattingLesson = $this->createLesson([
            'title' => 'Text Formatting',
            'slug' => 'text-formatting',
            'content' => json_encode([
                'sections' => [
                    [
                        'title' => 'Text Formatting Elements',
                        'content' => 'Learn how to format text in HTML using various elements.'
                    ]
                ],
                'navigation' => [
                    'next' => 'lists-and-tables',
                    'previous' => 'elements-and-tags'
                ]
            ]),
            'language_id' => $html->id,
            'level_id' => $beginner->id,
            'order' => 3,
            'estimated_time' => 15,
            'is_published' => true
        ]);

        // Intermediate Level Lessons
        $formsLesson = $this->createLesson([
            'title' => 'HTML Forms',
            'slug' => 'forms',
            'content' => json_encode([
                'sections' => [
                    [
                        'title' => 'Creating Forms',
                        'content' => 'Learn how to create interactive forms in HTML.'
                    ]
                ],
                'navigation' => [
                    'next' => 'semantic-html',
                    'previous' => 'text-formatting'
                ]
            ]),
            'language_id' => $html->id,
            'level_id' => $intermediate->id,
            'order' => 1,
            'estimated_time' => 25,
            'is_published' => true
        ]);

        // Expert Level Lessons
        $apisLesson = $this->createLesson([
            'title' => 'HTML5 APIs',
            'slug' => 'html5-apis',
            'content' => json_encode([
                'sections' => [
                    [
                        'title' => 'Modern HTML5 Features',
                        'content' => 'Explore advanced HTML5 APIs and features.'
                    ]
                ],
                'navigation' => [
                    'next' => 'web-storage',
                    'previous' => 'forms'
                ]
            ]),
            'language_id' => $html->id,
            'level_id' => $expert->id,
            'order' => 1,
            'estimated_time' => 30,
            'is_published' => true
        ]);

        $this->command->info('HTML lessons seeded successfully!');
    }

    private function createLesson($data)
    {
        return Lesson::updateOrCreate(['slug' => $data['slug']], $data);
    }

    private function addQuestions($lesson, $questions)
    {
        foreach ($questions as $questionData) {
            Question::create([
                'lesson_id' => $lesson->id,
                'question' => $questionData['question'],
                'type' => $questionData['type'],
                'question_text' => $questionData['question'],
                'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                'correct_answer' => $questionData['correct_answer'],
                'points' => $questionData['points'],
                'explanation' => $questionData['explanation'] ?? null,
                'difficulty' => $questionData['difficulty'] ?? 1
            ]);
        }
    }

    private function addExercises($lesson, $exercises)
    {
        foreach ($exercises as $exercise) {
            Exercise::create([
                'lesson_id' => $lesson->id,
                'title' => $exercise['title'],
                'description' => $exercise['description'],
                'instructions' => $exercise['instructions'],
                'starter_code' => $exercise['starter_code'] ?? null,
                'points' => $exercise['points'],
                'difficulty' => $exercise['difficulty'],
                'order' => $exercise['order']
            ]);
        }
    }
}