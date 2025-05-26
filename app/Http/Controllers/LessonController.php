<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class LessonController extends Controller
{
    //
    public function show($courseSlug, $lessonId)
    {
        $lesson = Lesson::with(['exercises'])
            ->whereHas('module.course', function ($query) use ($courseSlug) {
                $query->where('slug', $courseSlug);
            })
            ->findOrFail($lessonId);

        return new LessonResource($lesson);
    }

    public function complete($lessonId)
    {
        $user = auth()->user();
        $lesson = Lesson::findOrFail($lessonId);

        UserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'course_id' => $lesson->module->course_id
            ],
            [
                'status' => 'completed',
                'completed_at' => now()
            ]
        );

        return response()->json(['message' => 'Lesson completed successfully']);
    }
}
