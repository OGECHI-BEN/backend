<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\UserProgress;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function start($language, $level, $lessonSlug)
    {
        $questions = Question::with(['lesson'])
            ->whereHas('lesson.language', function ($query) use ($language) {
                $query->where('slug', $language);
            })
            ->whereHas('lesson.level', function ($query) use ($level) {
                $query->where('slug', $level);
            })
            ->whereHas('lesson', function ($query) use ($lessonSlug) {
                $query->where('slug', $lessonSlug);
            })
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return response()->json($questions);
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'language_id' => 'required|exists:languages,id',
            'level_id' => 'required|exists:levels,id',
            'lesson_id' => 'required|exists:lessons,id',
            'answers' => 'required|array',
            'time_taken' => 'required|integer'
        ]);

        $score = $this->calculateScore($validated['answers']);

        $quizAttempt = QuizAttempt::create([
            'user_id' => auth()->id(),
            'language_id' => $validated['language_id'],
            'level_id' => $validated['level_id'],
            'lesson_id' => $validated['lesson_id'],
            'score' => $score,
            'time_taken' => $validated['time_taken'],
            'completed_at' => now()
        ]);

        // Update user progress
        UserProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'lesson_id' => $validated['lesson_id'],
                'language_id' => $validated['language_id'],
                'level_id' => $validated['level_id']
            ],
            [
                'completed' => true,
                'score' => $score,
                'completed_at' => now()
            ]
        );

        return response()->json([
            'score' => $score,
            'quiz_attempt' => $quizAttempt,
            'leaderboard_position' => $this->getLeaderboardPosition($quizAttempt)
        ]);
    }

    private function calculateScore($answers)
    {
        $score = 0;
        foreach ($answers as $questionId => $answer) {
            $question = Question::find($questionId);
            if ($question && $this->isCorrectAnswer($question, $answer)) {
                $score += $question->points;
            }
        }
        return $score;
    }

    private function isCorrectAnswer($question, $answer)
    {
        if ($question->type === 'multiple-choice') {
            return $answer === $question->correct_answer;
        } else {
            // For code questions, implement more sophisticated comparison
            return trim($answer) === trim($question->correct_answer);
        }
    }

    private function getLeaderboardPosition($quizAttempt)
    {
        return QuizAttempt::where('language_id', $quizAttempt->language_id)
            ->where('level_id', $quizAttempt->level_id)
            ->where('score', '>', $quizAttempt->score)
            ->count() + 1;
    }
}

