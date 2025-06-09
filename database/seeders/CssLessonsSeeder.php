<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Exercise; // Fixed typo: was "Excercise"

class CssLessonsSeeder extends Seeder
{
    public function run()
    {
        // Create CSS language if it doesn't exist
        $css = Language::firstOrCreate(
            ['slug' => 'css'],
            [
                'name' => 'CSS',
                'description' => 'Cascading Style Sheets',
                'icon' => 'css3',
                'color' => '#1572B6', // Added missing fields
                'is_active' => true,
                'order' => 1
            ]
        );

        // Create levels if they don't exist
        $beginner = Level::firstOrCreate(
            ['slug' => 'beginner'],
            [
                'name' => 'Beginner',
                'description' => 'Perfect for those new to CSS',
                'order' => 1
            ]
        );

        $intermediate = Level::firstOrCreate(
            ['slug' => 'intermediate'],
            [
                'name' => 'Intermediate',
                'description' => 'For those with basic CSS knowledge',
                'order' => 2
            ]
        );

        $expert = Level::firstOrCreate(
            ['slug' => 'expert'],
            [
                'name' => 'Expert',
                'description' => 'Advanced CSS concepts and techniques',
                'order' => 3
            ]
        );

        // Create lessons for each level
        $this->createBeginnerLessons($css, $beginner);
        $this->createIntermediateLessons($css, $intermediate);
        $this->createExpertLessons($css, $expert);
    }

    private function createBeginnerLessons($language, $level)
    {
        $lessons = [
            [
                'title' => 'Introduction to CSS',
                'slug' => 'introduction',
                'content' => json_encode($this->getIntroductionContent()), // Convert to JSON
                'order' => 1,
                'exercises' => [
                    [
                        'title' => 'Your First CSS Styles',
                        'description' => 'Create a basic CSS file and style a paragraph with color and font-size',
                        'points' => 10,
                        'test_cases' => json_encode([ // Convert to JSON
                            [
                                'input' => 'p {\n  color: blue;\n  font-size: 16px;\n}',
                                'expected_output' => true
                            ]
                        ])
                    ]
                ],
                'questions' => [
                    [
                        'question' => 'What does CSS stand for?',
                        'type' => 'multiple-choice',
                        'options' => json_encode([ // Convert to JSON
                            'Cascading Style Sheets',
                            'Computer Style Sheets',
                            'Creative Style Sheets',
                            'Colorful Style Sheets'
                        ]),
                        'correct_answer' => 'Cascading Style Sheets',
                        'points' => 5
                    ]
                ]
            ],
            [
                'title' => 'CSS Selectors',
                'slug' => 'selectors',
                'content' => json_encode($this->getSelectorsContent()),
                'order' => 2,
                'exercises' => [
                    [
                        'title' => 'Using Different Selectors',
                        'description' => 'Create styles using element, class, and ID selectors',
                        'points' => 15,
                        'test_cases' => json_encode([
                            [
                                'input' => '.highlight {\n  background-color: yellow;\n}\n#header {\n  font-size: 24px;\n}',
                                'expected_output' => true
                            ]
                        ])
                    ]
                ],
                'questions' => [
                    [
                        'question' => 'Which symbol is used for class selectors?',
                        'type' => 'multiple-choice',
                        'options' => json_encode(['.', '#', '@', '*']),
                        'correct_answer' => '.',
                        'points' => 5
                    ]
                ]
            ]
        ];

        $this->createLessons($lessons, $language, $level);
    }

    private function createIntermediateLessons($language, $level)
    {
        $lessons = [
            [
                'title' => 'CSS Box Model',
                'slug' => 'box-model',
                'content' => json_encode($this->getBoxModelContent()),
                'order' => 1,
                'exercises' => [
                    [
                        'title' => 'Create a Card Layout',
                        'description' => 'Create a card with proper padding, margin, and border',
                        'points' => 20,
                        'test_cases' => json_encode([
                            [
                                'input' => '.card {\n  padding: 20px;\n  margin: 10px;\n  border: 1px solid #ccc;\n  border-radius: 5px;\n}',
                                'expected_output' => true
                            ]
                        ])
                    ]
                ],
                'questions' => [
                    [
                        'question' => 'Which property controls the space between the content and the border?',
                        'type' => 'multiple-choice',
                        'options' => json_encode(['padding', 'margin', 'border', 'spacing']),
                        'correct_answer' => 'padding',
                        'points' => 5
                    ]
                ]
            ],
            [
                'title' => 'Flexbox Layout',
                'slug' => 'flexbox',
                'content' => json_encode($this->getFlexboxContent()),
                'order' => 2,
                'exercises' => [
                    [
                        'title' => 'Create a Flex Navigation',
                        'description' => 'Create a horizontal navigation using flexbox',
                        'points' => 25,
                        'test_cases' => json_encode([
                            [
                                'input' => '.nav {\n  display: flex;\n  justify-content: space-between;\n  align-items: center;\n}',
                                'expected_output' => true
                            ]
                        ])
                    ]
                ],
                'questions' => [
                    [
                        'question' => 'Which property is used to create a flex container?',
                        'type' => 'multiple-choice',
                        'options' => json_encode(['display: flex', 'flex: 1', 'flexbox: true', 'layout: flex']),
                        'correct_answer' => 'display: flex',
                        'points' => 5
                    ]
                ]
            ]
        ];

        $this->createLessons($lessons, $language, $level);
    }

    private function createExpertLessons($language, $level)
    {
        $lessons = [
            [
                'title' => 'CSS Grid',
                'slug' => 'grid',
                'content' => json_encode($this->getGridContent()),
                'order' => 1,
                'exercises' => [
                    [
                        'title' => 'Create a Grid Layout',
                        'description' => 'Create a responsive grid layout with named areas',
                        'points' => 30,
                        'test_cases' => json_encode([
                            [
                                'input' => '.grid {\n  display: grid;\n  grid-template-columns: repeat(3, 1fr);\n  grid-gap: 20px;\n}',
                                'expected_output' => true
                            ]
                        ])
                    ]
                ],
                'questions' => [
                    [
                        'question' => 'Which property defines the size of grid columns?',
                        'type' => 'multiple-choice',
                        'options' => json_encode([
                            'grid-template-columns',
                            'grid-columns',
                            'columns',
                            'grid-size'
                        ]),
                        'correct_answer' => 'grid-template-columns',
                        'points' => 5
                    ]
                ]
            ],
            [
                'title' => 'CSS Animations',
                'slug' => 'animations',
                'content' => json_encode($this->getAnimationsContent()),
                'order' => 2,
                'exercises' => [
                    [
                        'title' => 'Create a Loading Animation',
                        'description' => 'Create a spinning loading animation using keyframes',
                        'points' => 35,
                        'test_cases' => json_encode([
                            [
                                'input' => '@keyframes spin {\n  from { transform: rotate(0deg); }\n  to { transform: rotate(360deg); }\n}\n.loader {\n  animation: spin 1s linear infinite;\n}',
                                'expected_output' => true
                            ]
                        ])
                    ]
                ],
                'questions' => [
                    [
                        'question' => 'Which property is used to define a keyframe animation?',
                        'type' => 'multiple-choice',
                        'options' => json_encode(['@keyframes', '@animation', '@frames', '@animate']),
                        'correct_answer' => '@keyframes',
                        'points' => 5
                    ]
                ]
            ]
        ];

        $this->createLessons($lessons, $language, $level);
    }

    private function createLessons($lessons, $language, $level)
    {
        foreach ($lessons as $lessonData) {
            $lesson = Lesson::create([
                'title' => $lessonData['title'],
                'slug' => $lessonData['slug'],
                'content' => is_string($lessonData['content']) ? $lessonData['content'] : json_encode($lessonData['content']),
                'order' => $lessonData['order'],
                'language_id' => $language->id,
                'level_id' => $level->id,
                'estimated_time' => 10
            ]);

            // Create exercises
            foreach ($lessonData['exercises'] as $exerciseData) {
                Exercise::create([
                    'title' => $exerciseData['title'],
                    'description' => $exerciseData['description'],
                    'points' => $exerciseData['points'],
                    'test_cases' => is_string($exerciseData['test_cases']) ? $exerciseData['test_cases'] : json_encode($exerciseData['test_cases']),
                    'lesson_id' => $lesson->id
                ]);
            }

            // Create questions
            foreach ($lessonData['questions'] as $questionData) {
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
    }

    private function getIntroductionContent()
    {
        return [
            'sections' => [
                [
                    'title' => 'What is CSS?',
                    'content' => 'CSS (Cascading Style Sheets) is a style sheet language used for describing the presentation of a document written in HTML...',
                    'code_example' => 'body {\n  font-family: Arial, sans-serif;\n  line-height: 1.6;\n  color: #333;\n}'
                ],
                [
                    'title' => 'Basic Syntax',
                    'content' => 'CSS consists of selectors and declaration blocks...',
                    'code_example' => 'selector {\n  property: value;\n  property: value;\n}'
                ]
            ]
        ];
    }

    private function getSelectorsContent()
    {
        return [
            'sections' => [
                [
                    'title' => 'Types of Selectors',
                    'content' => 'CSS provides various ways to select elements...',
                    'code_example' => '/* Element selector */\np { color: blue; }\n\n/* Class selector */\n.highlight { background: yellow; }\n\n/* ID selector */\n#header { font-size: 24px; }'
                ],
                [
                    'title' => 'Combining Selectors',
                    'content' => 'You can combine selectors to create more specific rules...',
                    'code_example' => '/* Descendant selector */\ndiv p { margin: 10px; }\n\n/* Child selector */\ndiv > p { padding: 5px; }\n\n/* Adjacent sibling selector */\nh2 + p { font-weight: bold; }'
                ]
            ]
        ];
    }

    private function getBoxModelContent()
    {
        return [
            'sections' => [
                [
                    'title' => 'Understanding the Box Model',
                    'content' => 'Every element in CSS is a box with content, padding, border, and margin...',
                    'code_example' => '.box {\n  width: 200px;\n  padding: 20px;\n  border: 2px solid black;\n  margin: 10px;\n  box-sizing: border-box;\n}'
                ],
                [
                    'title' => 'Box Sizing',
                    'content' => 'The box-sizing property determines how the width and height are calculated...',
                    'code_example' => '* {\n  box-sizing: border-box;\n}\n\n.content-box {\n  box-sizing: content-box;\n}'
                ]
            ]
        ];
    }

    private function getFlexboxContent()
    {
        return [
            'sections' => [
                [
                    'title' => 'Flexbox Basics',
                    'content' => 'Flexbox is a one-dimensional layout method for laying out items in rows or columns...',
                    'code_example' => '.container {\n  display: flex;\n  justify-content: space-between;\n  align-items: center;\n  flex-wrap: wrap;\n}'
                ],
                [
                    'title' => 'Flex Properties',
                    'content' => 'Flex items can be controlled using various properties...',
                    'code_example' => '.item {\n  flex: 1;\n  order: 2;\n  align-self: flex-start;\n}'
                ]
            ]
        ];
    }

    private function getGridContent()
    {
        return [
            'sections' => [
                [
                    'title' => 'Grid Basics',
                    'content' => 'CSS Grid is a two-dimensional layout system...',
                    'code_example' => '.grid {\n  display: grid;\n  grid-template-columns: repeat(3, 1fr);\n  grid-gap: 20px;\n  grid-template-areas:\n    "header header header"\n    "sidebar main main"\n    "footer footer footer";\n}'
                ],
                [
                    'title' => 'Grid Areas',
                    'content' => 'Grid areas allow you to create complex layouts...',
                    'code_example' => '.header { grid-area: header; }\n.sidebar { grid-area: sidebar; }\n.main { grid-area: main; }\n.footer { grid-area: footer; }'
                ]
            ]
        ];
    }

    private function getAnimationsContent()
    {
        return [
            'sections' => [
                [
                    'title' => 'Keyframe Animations',
                    'content' => 'CSS animations allow you to create complex animations...',
                    'code_example' => '@keyframes slide {\n  from { transform: translateX(-100%); }\n  to { transform: translateX(0); }\n}\n\n.element {\n  animation: slide 1s ease-out;\n}'
                ],
                [
                    'title' => 'Animation Properties',
                    'content' => 'Control animations with various properties...',
                    'code_example' => '.animated {\n  animation-name: bounce;\n  animation-duration: 2s;\n  animation-timing-function: ease-in-out;\n  animation-iteration-count: infinite;\n  animation-direction: alternate;\n}'
                ]
            ]
        ];
    }
}
