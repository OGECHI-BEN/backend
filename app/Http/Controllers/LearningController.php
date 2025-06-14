<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\UserProgress;
use App\Models\UserQuizAttempt;
use App\Models\Exercise;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LearningController extends Controller
{
    /**
     * Display the learning dashboard
     */
    public function index()
    {
        $languages = Language::with(['levels.lessons'])->get();
        $userProgress = Auth::user()->progress()->with('lesson')->get();

        return view('learning.index', compact('languages', 'userProgress'));
    }

    /**
     * Get all available languages
     */
    public function getLanguages()
    {
        $languages = Language::with(['lessons', 'quizzes'])->get();
        return response()->json($languages);
    }

    /**
     * Get lessons for a specific language
     */
    public function getLessonsByLanguage($languageSlug)
    {
        $language = Language::where('slug', $languageSlug)->firstOrFail();
        $lessons = Lesson::where('language_id', $language->id)
            ->with(['level', 'userProgress' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->orderBy('level_id')
            ->orderBy('order')
            ->get();

        return response()->json($lessons);
    }

    /**
     * Get a specific lesson
     */
    public function getLesson($languageSlug, $lessonSlug)
    {
        $language = Language::where('slug', $languageSlug)->firstOrFail();
        $lesson = Lesson::where('language_id', $language->id)
            ->where('slug', $lessonSlug)
            ->with(['level', 'language', 'questions', 'exercises'])
            ->firstOrFail();

        // --- CHANGE START: Corrected UserProgress query to use polymorphic relation ---
        $userProgress = UserProgress::where('user_id', Auth::id())
            ->where('progressable_id', $lesson->id)
            ->where('progressable_type', Lesson::class)
            ->first();
        // --- CHANGE END ---

        $nextLesson = $lesson->getNextLesson();
        $previousLesson = $lesson->getPreviousLesson();

        return response()->json([
            'lesson' => $lesson,
            'user_progress' => $userProgress,
            'navigation' => [
                'next' => $nextLesson,
                'previous' => $previousLesson,
            ],
        ]);
    }

    /**
     * Submit an answer to a question
     */
    public function submitAnswer(Request $request, $lessonId, $questionId)
    {
        // Get the authenticated user instance
        $user = Auth::user();

        // Check if the user is authenticated. If not, return an unauthorized response.
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 1. Validate the incoming request data.
        //    Ensure that an 'answer' field is present and is a string.
        $validator = Validator::make($request->all(), [
            'answer' => 'required|string',
        ]);

        // If validation fails, return a JSON response with the validation errors.
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Retrieve the question from the database using its ID.
        //    If the question is not found, Laravel's findOrFail method will automatically
        //    throw a ModelNotFoundException, resulting in a 404 Not Found response.
        $question = Question::findOrFail($questionId);

        // --- NEW CHECK START ---
        // Check if the user has already completed this specific question.
        $existingUserProgressForQuestion = UserProgress::where('user_id', $user->id)
            ->where('progressable_id', $question->id)
            ->where('progressable_type', Question::class)
            ->where('status', 'completed')
            ->first();

        if ($existingUserProgressForQuestion) {
            return response()->json([
                'is_correct' => true, // Treat as correct since it was already completed
                'message' => 'This question has already been submitted and completed successfully.',
                'points' => $question->points, // Return the points they would have earned
                'status' => 'already_completed' // Custom status for frontend handling
            ], 200);
        }
        // --- NEW CHECK END ---

        // 3. Determine if the submitted answer is correct by comparing it
        //    with the 'correct_answer' stored in the question model.
        $isCorrect = $request->answer === $question->correct_answer;

        // 4. Update or create the user's progress ONLY if the submitted answer is correct.
        //    This change ensures that user progress (e.g., status, score, completion time)
        //    is not modified for incorrect attempts, adhering to the requirement
        //    "prevent updates if the answers are wrong".
        if ($isCorrect) {
            // Find the existing UserProgress record for the LESSON or create a new instance if it doesn't exist.
            // This is for lesson-level progress accumulation.
            $lessonUserProgress = UserProgress::firstOrNew(
                [
                    'user_id' => $user->id,
                    'progressable_id' => $lessonId,
                    'progressable_type' => Lesson::class,
                ]
            );

            // Calculate the new score by adding the current question's points to the existing lesson score.
            $newLessonScore = ($lessonUserProgress->score ?? 0) + $question->points;

            // Update or create the user's progress for the LESSON.
            UserProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'progressable_id' => $lessonId,
                    'progressable_type' => Lesson::class,
                ],
                [
                    'lesson_id' => $lessonId,
                    'status' => 'completed', // Can refine this to track 'in_progress' vs 'completed' for lessons
                    'score' => $newLessonScore,
                    'completed_at' => now(), // This might be better set when all questions/exercises in lesson are done
                    'last_accessed_at' => now(),
                ]
            );

            // Create/Update UserProgress specifically for THIS QUESTION.
            // This is crucial for the `is_completed` flag on individual questions.
            UserProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'progressable_id' => $question->id,
                    'progressable_type' => Question::class,
                ],
                [
                    'lesson_id' => $lessonId, // Link to the lesson for easier querying
                    'status' => 'completed',
                    'score' => $question->points, // Score for this specific question
                    'completed_at' => now(),
                    'last_accessed_at' => now(),
                ]
            );

            // Increment the 'points' column on the user's model.
            // This assumes your User model has a 'points' column.
            $user->increment('points', $question->points);

            // The 'dd($user->points)' line is for debugging and should be removed in production.
            // dd($user->points);
        }

        // 5. Return a JSON response to the client.
        //    This response includes whether the answer was correct, a user-friendly message,
        //    and the points earned (0 if incorrect, question points if correct).
        return response()->json([
            'is_correct' => $isCorrect,
            'message' => $isCorrect ? 'Correct! Well done.' : 'Incorrect. Please review the material and try again.',
            'points' => $isCorrect ? $question->points : 0,
            'status' => $isCorrect ? 'completed' : 'incorrect'
        ]);
    }
    /**
     * Submit an exercise solution
     */
    public function submitExercise(Request $request, $lessonId, $exerciseId)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exercise = Exercise::findOrFail($exerciseId);

        // Here you would implement your code validation logic
        $isCorrect = $this->validateExerciseSolution($request->code, $exercise);

        // --- CHANGE START: Corrected UserProgress update/create to use polymorphic relation and correct fields ---
        $progress = UserProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'progressable_id' => $lessonId,
                'progressable_type' => Lesson::class, // Specify the type
            ],
            [
                'status' => 'completed', // Changed from 'completed' to 'status'
                'score' => $isCorrect ? $exercise->points : 0, // Changed from 'points_earned' to 'score'
                'completed_at' => $isCorrect ? now() : null, // Only mark completed if correct
                'last_accessed_at' => now(),
            ]
        );
        // --- CHANGE END ---

        // --- CHANGE START: Changed return format to match frontend expectation ---
        return response()->json([
            'is_correct' => $isCorrect,
            'message' => 'Exercise completed successfully!', // Changed to 'is_correct' and 'message'
            'points' => $isCorrect ? $exercise->points : 0
        ]);
        // --- CHANGE END ---
    }

    /**
     * Complete a lesson
     */
    public function completeLesson(Request $request, $languageSlug, $lessonSlug)
    {
        $language = Language::where('slug', $languageSlug)->firstOrFail();
        $lesson = Lesson::where('language_id', $language->id)
            ->where('slug', $lessonSlug)
            ->firstOrFail();

        // --- CHANGE START: Corrected UserProgress update/create to use polymorphic relation and correct fields ---
        $userProgress = UserProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'progressable_id' => $lesson->id,
                'progressable_type' => Lesson::class, // Specify the type
            ],
            [
                'status' => 'completed', // Changed from 'completed' to 'status'
                'score' => $lesson->points, // Changed from 'points_earned' to 'score'
                'completed_at' => now(),
                'last_accessed_at' => now(),
            ]
        );
        // --- CHANGE END ---
        return response()->json($userProgress);
    }

    /**
     * Get quizzes for a specific language
     */
    public function getQuizzesByLanguage($languageSlug)
    {
        $language = Language::where('slug', $languageSlug)->firstOrFail();
        $quizzes = Quiz::where('language_id', $language->id)
            ->with(['level', 'questions.answers'])
            ->get();

        return response()->json($quizzes);
    }

    /**
     * Get a specific quiz
     */
    public function getQuiz($languageSlug, $quizId)
    {
        $language = Language::where('slug', $languageSlug)->firstOrFail();
        $quiz = Quiz::where('language_id', $language->id)
            ->with(['questions.answers'])
            ->findOrFail($quizId);

        return response()->json($quiz);
    }

    /**
     * Submit a quiz
     */
    public function submitQuiz(Request $request, $languageSlug, $quizId)
    {
        $language = Language::where('slug', $languageSlug)->firstOrFail();
        $quiz = Quiz::where('language_id', $language->id)
            ->with('questions')
            ->findOrFail($quizId);

        $answers = $request->input('answers');
        $score = 0;
        $correctAnswers = 0;

        foreach ($quiz->questions as $question) {
            if (isset($answers[$question->id])) {
                $answer = $question->answers()
                    ->where('id', $answers[$question->id])
                    ->where('is_correct', true)
                    ->first();

                if ($answer) {
                    $score += $question->points;
                    $correctAnswers++;
                }
            }
        }

        $passed = ($score / $quiz->getTotalPoints()) * 100 >= $quiz->passing_score;
        $pointsEarned = $passed ? $quiz->points : 0;

        $attempt = UserQuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'points_earned' => $pointsEarned,
            'passed' => $passed,
            'started_at' => $request->input('started_at'),
            'completed_at' => now(),
        ]);

        foreach ($answers as $questionId => $answerId) {
            $question = $quiz->questions()->find($questionId);
            $isCorrect = $question->answers()
                ->where('id', $answerId)
                ->where('is_correct', true)
                ->exists();

            $attempt->answers()->create([
                'question_id' => $questionId,
                'answer_id' => $answerId,
                'is_correct' => $isCorrect,
            ]);
        }

        return response()->json([
            'attempt' => $attempt,
            'score' => $score,
            'total_points' => $quiz->getTotalPoints(),
            'passed' => $passed,
            'points_earned' => $pointsEarned,
        ]);
    }

    /**
     * Get user's learning progress
     */
    public function getProgress()
    {
        $progress = Auth::user()->progress()
            ->with(['lesson.language', 'lesson.level'])
            ->get();

        return response()->json([
            'progress' => $progress
        ]);
    }

    /**
     * Validate exercise solution
     * This is a placeholder - you would implement actual code validation logic
     */
    private function validateExerciseSolution($code, $exercise)
    {
        // IMPORTANT: Implement your actual code validation logic here based on the exercise content and type.
        // For now, this is a basic example checking for HTML tags from the Text Formatting lesson.
        // For a robust solution, you would typically store `expected_output` or `test_cases` directly in the `exercises` table.

        // Example for 'Text Formatting Practice' exercise (ID may vary, checking by title/description for now)
        // This assumes the exercise requires specific HTML tags.
        if (str_contains($exercise->title, 'Text Formatting Practice')) {
            $requiredTags = ['<strong>', '<em>', '<mark>']; // Basic set of tags to check for
            $allTagsPresent = true;

            foreach ($requiredTags as $tag) {
                if (!str_contains($code, $tag)) {
                    $allTagsPresent = false;
                    break;
                }
            }
            return $allTagsPresent;
        }

        // You would add more `if/else if` blocks for different exercise types or IDs
        // For example, if you have an 'html_structure' exercise type, you might check for specific DOM structure.
        // if ($exercise->type === 'html_structure') {
        //     // Use a PHP HTML parser here to validate DOM structure
        //     return $this->validateHtmlStructure($code, $exercise->expected_structure);
        // }

        // Fallback for exercises without specific validation logic (e.g., new exercises)
        return false; // By default, assume incorrect if no specific validation is implemented.
    }
}