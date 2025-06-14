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
                'question_text' => 'What does HTML stand for?',
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
                'question_text' => 'Which tag is used to create a paragraph?',
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
                'question_text' => 'Write the basic structure of an HTML document.',
                'correct_answer' => '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n</body>\n</html>',
                'points' => 2
            ]
        ]);

        $this->addQuestions($elementsLesson, [
            [
                'type' => 'multiple-choice',
                'question_text' => 'Which tag is used for the largest heading?',
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
                'question_text' => 'What is the correct HTML for creating a hyperlink?',
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
                'question_text' => 'Create an image tag that displays an image named "logo.png" with alt text "Company Logo".',
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
                        'content' => 'Learn how to format text in HTML using various elements.',
                        'items' => [
                            [
                                'code' => '<strong>',
                                'description' => 'Makes text bold'
                            ],
                            [
                                'code' => '<em>',
                                'description' => 'Makes text italic'
                            ],
                            [
                                'code' => '<mark>',
                                'description' => 'Highlights text'
                            ],
                            [
                                'code' => '<small>',
                                'description' => 'Makes text smaller'
                            ],
                            [
                                'code' => '<del>',
                                'description' => 'Shows deleted text'
                            ],
                            [
                                'code' => '<ins>',
                                'description' => 'Shows inserted text'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Text Formatting Example',
                        'codeExample' => '<p>This is <strong>bold</strong> and this is <em>italic</em>.</p>
<p>This is <mark>highlighted</mark> and this is <small>small</small>.</p>
<p>This is <del>deleted</del> and this is <ins>inserted</ins>.</p>'
                    ]
                ],
                'exercise' => [
                    'description' => 'Create a formatted text document using various text formatting elements.',
                    'requirements' => [
                        'Use at least 3 different text formatting elements',
                        'Create a paragraph with mixed formatting',
                        'Demonstrate the use of deleted and inserted text'
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

        // Add questions for Text Formatting lesson
        $this->addQuestions($textFormattingLesson, [
            [
                'type' => 'multiple-choice',
                'question_text' => 'Which tag is used to make text bold?',
                'options' => [
                    '<bold>',
                    '<strong>',
                    '<b>',
                    '<em>'
                ],
                'correct_answer' => '<strong>',
                'points' => 1
            ],
            [
                'type' => 'multiple-choice',
                'question_text' => 'What is the difference between <b> and <strong>?',
                'options' => [
                    'There is no difference',
                    '<strong> is semantic while <b> is just visual',
                    '<b> is newer than <strong>',
                    '<strong> is deprecated'
                ],
                'correct_answer' => '<strong> is semantic while <b> is just visual',
                'points' => 2
            ],
            [
                'type' => 'code',
                'question_text' => 'Create a paragraph with bold, italic, and highlighted text.',
                'correct_answer' => '<p>This is <strong>bold</strong>, this is <em>italic</em>, and this is <mark>highlighted</mark>.</p>',
                'points' => 2
            ]
        ]);

        // Add exercises for Text Formatting lesson
        $this->addExercises($textFormattingLesson, [
            [
                'title' => 'Text Formatting Practice',
                'description' => 'Practice using various text formatting elements',
                'instructions' => 'Create a formatted text document with multiple formatting elements',
                'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n    <title>Text Formatting</title>\n</head>\n<body>\n\n</body>\n</html>',
                'points' => 5,
                'difficulty' => 'beginner',
                'order' => 1
            ],
            [
                'title' => 'Formatting Quiz',
                'description' => 'Test your knowledge of text formatting elements',
                'instructions' => 'Identify the correct usage of text formatting elements',
                'points' => 3,
                'difficulty' => 'beginner',
                'order' => 2
            ]
        ]);

        // Intermediate Level Lessons
        $formsLesson = $this->createLesson([
            'title' => 'HTML Forms',
            'slug' => 'forms',
            'content' => json_encode([
                'sections' => [
                    [
                        'title' => 'Creating Forms',
                        'content' => 'Learn how to create interactive forms in HTML.',
                        'items' => [
                            [
                                'code' => '<form>',
                                'description' => 'Container for form elements'
                            ],
                            [
                                'code' => '<input>',
                                'description' => 'Input field for user data'
                            ],
                            [
                                'code' => '<label>',
                                'description' => 'Label for form controls'
                            ],
                            [
                                'code' => '<select>',
                                'description' => 'Dropdown list'
                            ],
                            [
                                'code' => '<textarea>',
                                'description' => 'Multi-line text input'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Form Example',
                        'codeExample' => '<form action="/submit" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="message">Message:</label>
    <textarea id="message" name="message" rows="4"></textarea>

    <button type="submit">Submit</button>
</form>'
                    ]
                ],
                'exercise' => [
                    'description' => 'Create a contact form with various input types.',
                    'requirements' => [
                        'Include text, email, and textarea inputs',
                        'Add proper labels for each field',
                        'Include a submit button',
                        'Add form validation attributes'
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

        // Add questions for Forms lesson
        $this->addQuestions($formsLesson, [
            [
                'type' => 'multiple-choice',
                'question_text' => 'Which attribute is used to specify where to send form data?',
                'options' => [
                    'method',
                    'action',
                    'target',
                    'url'
                ],
                'correct_answer' => 'action',
                'points' => 1
            ],
            [
                'type' => 'multiple-choice',
                'question_text' => 'What is the purpose of the "required" attribute?',
                'options' => [
                    'Makes the field optional',
                    'Makes the field mandatory',
                    'Sets a default value',
                    'Validates the input format'
                ],
                'correct_answer' => 'Makes the field mandatory',
                'points' => 1
            ],
            [
                'type' => 'code',
                'question_text' => 'Create a form with a text input, email input, and submit button.',
                'correct_answer' => '<form action="/submit" method="post">\n    <input type="text" name="name" required>\n    <input type="email" name="email" required>\n    <button type="submit">Submit</button>\n</form>',
                'points' => 2
            ]
        ]);

        // Add exercises for Forms lesson
        $this->addExercises($formsLesson, [
            [
                'title' => 'Contact Form',
                'description' => 'Create a complete contact form',
                'instructions' => 'Build a contact form with various input types and validation',
                'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n    <title>Contact Form</title>\n</head>\n<body>\n\n</body>\n</html>',
                'points' => 5,
                'difficulty' => 'intermediate',
                'order' => 1
            ],
            [
                'title' => 'Form Validation',
                'description' => 'Practice form validation techniques',
                'instructions' => 'Add validation to your contact form',
                'points' => 3,
                'difficulty' => 'intermediate',
                'order' => 2
            ]
        ]);

        // Expert Level Lessons
        $apisLesson = $this->createLesson([
            'title' => 'HTML5 APIs',
            'slug' => 'html5-apis',
            'content' => json_encode([
                'sections' => [
                    [
                        'title' => 'Modern HTML5 Features',
                        'content' => 'Explore advanced HTML5 APIs and features.',
                        'items' => [
                            [
                                'code' => 'Geolocation API',
                                'description' => 'Access user location'
                            ],
                            [
                                'code' => 'Web Storage',
                                'description' => 'Store data in the browser'
                            ],
                            [
                                'code' => 'Web Workers',
                                'description' => 'Run scripts in background'
                            ],
                            [
                                'code' => 'WebSocket',
                                'description' => 'Real-time communication'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Geolocation Example',
                        'codeExample' => 'if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            console.log(`Latitude: ${latitude}, Longitude: ${longitude}`);
        },
        (error) => {
            console.error("Error getting location:", error);
        }
    );
}'
                    ]
                ],
                'exercise' => [
                    'description' => 'Implement a feature using HTML5 APIs.',
                    'requirements' => [
                        'Choose one HTML5 API to implement',
                        'Create a working example',
                        'Handle errors appropriately',
                        'Add user feedback'
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

        // Add questions for HTML5 APIs lesson
        $this->addQuestions($apisLesson, [
            [
                'type' => 'multiple-choice',
                'question_text' => 'Which API allows you to store data in the browser?',
                'options' => [
                    'Geolocation API',
                    'Web Storage API',
                    'Web Workers API',
                    'WebSocket API'
                ],
                'correct_answer' => 'Web Storage API',
                'points' => 1
            ],
            [
                'type' => 'multiple-choice',
                'question_text' => 'What is the main advantage of Web Workers?',
                'options' => [
                    'Better UI performance',
                    'Offline functionality',
                    'Real-time updates',
                    'Background processing'
                ],
                'correct_answer' => 'Background processing',
                'points' => 2
            ],
            [
                'type' => 'code',
                'question_text' => 'Write code to store and retrieve data using localStorage.',
                'correct_answer' => '// Store data\nlocalStorage.setItem("user", "John");\n\n// Retrieve data\nconst user = localStorage.getItem("user");',
                'points' => 2
            ]
        ]);

        // Add exercises for HTML5 APIs lesson
        $this->addExercises($apisLesson, [
            [
                'title' => 'Location Tracker',
                'description' => 'Create a location tracking application',
                'instructions' => 'Implement geolocation API to track user position',
                'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n    <title>Location Tracker</title>\n</head>\n<body>\n\n</body>\n</html>',
                'points' => 5,
                'difficulty' => 'expert',
                'order' => 1
            ],
            [
                'title' => 'Web Storage App',
                'description' => 'Build an app using Web Storage',
                'instructions' => 'Create a todo list that persists data using localStorage',
                'points' => 5,
                'difficulty' => 'expert',
                'order' => 2
            ]
        ]);
    }

    private function createLesson($data)
    {
        // First try to find the lesson
        $lesson = Lesson::where('slug', $data['slug'])->first();

        if ($lesson) {
            // If lesson exists, update it
            $lesson->update($data);
            return $lesson;
        }

        // If lesson doesn't exist, create it
        return Lesson::create($data);
    }

    private function addQuestions($lesson, $questions)
    {
        foreach ($questions as $question) {
            Question::create([
                'lesson_id' => $lesson->id,
                'type' => $question['type'],
                'question' => $question['question_text'],
                'question_text' => $question['question_text'],
                'correct_answer' => $question['correct_answer'],
                'explanation' => $question['explanation'] ?? null,
                'difficulty' => $question['difficulty'] ?? 1,
                'options' => isset($question['options']) ? json_encode($question['options']) : null,
                'points' => $question['points']
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