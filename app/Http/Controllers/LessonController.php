<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\UserProgress;
use App\Http\Resources\LessonResource;

class LessonController extends Controller
{
    /**
     * Get all lessons for a specific language and level
     */
    public function index($language, $level)
    {
        $lessons = Lesson::with(['language', 'level', 'questions'])
            ->whereHas('language', function ($query) use ($language) {
                $query->where('slug', $language);
            })
            ->whereHas('level', function ($query) use ($level) {
                $query->where('slug', $level);
            })
            ->orderBy('order')
            ->get();

        return LessonResource::collection($lessons);
    }

    /**
     * Get a specific lesson with its content and questions
     */
    public function show($language, $level, $lessonSlug)
    {
        $lesson = Lesson::with(['language', 'level', 'questions', 'exercises'])
            ->whereHas('language', function ($query) use ($language) {
                $query->where('slug', $language);
            })
            ->whereHas('level', function ($query) use ($level) {
                $query->where('slug', $level);
            })
            ->where('slug', $lessonSlug)
            ->firstOrFail();

        return new LessonResource($lesson);
    }

    /**
     * Mark a lesson as completed
     */
    public function complete($language, $level, $lessonSlug)
    {
        $user = auth()->user();
        $lesson = Lesson::whereHas('language', function ($query) use ($language) {
                $query->where('slug', $language);
            })
            ->whereHas('level', function ($query) use ($level) {
                $query->where('slug', $level);
            })
            ->where('slug', $lessonSlug)
            ->firstOrFail();

        UserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'language_id' => $lesson->language_id,
                'level_id' => $lesson->level_id
            ],
            [
                'completed' => true,
                'completed_at' => now()
            ]
        );

        return response()->json([
            'message' => 'Lesson completed successfully',
            'progress' => $this->calculateProgress($user, $lesson->language_id, $lesson->level_id)
        ]);
    }

    /**
     * Calculate user's progress for a specific language and level
     */
    private function calculateProgress($user, $languageId, $levelId)
    {
        $totalLessons = Lesson::where('language_id', $languageId)
            ->where('level_id', $levelId)
            ->count();

        $completedLessons = UserProgress::where('user_id', $user->id)
            ->where('language_id', $languageId)
            ->where('level_id', $levelId)
            ->where('completed', true)
            ->count();

        return $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
    }
}