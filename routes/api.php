<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\AchievementController;


Route::get('/test', function() {
    return response()->json(['status' => 'working']);
});
// Auth routes

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Course routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{slug}', [CourseController::class, 'show']);
    Route::get('/courses/{courseSlug}/lessons/{lessonId}', [LessonController::class, 'show']);

    // Content routes
    Route::prefix('content')->group(function () {
        // Lessons
        Route::get('/{language}/{level}', [LessonController::class, 'index']);
        Route::get('/{language}/{level}/{section}', [LessonController::class, 'show']);
        Route::post('/{language}/{level}/{section}/complete', [LessonController::class, 'complete']);
        Route::post('/lessons/{lessonId}/complete', [LessonController::class, 'complete']);

        // Exercises
        Route::post('/exercises/{exerciseId}/submit', [ExerciseController::class, 'submit']);
    });

    // Quiz routes
    Route::prefix('quiz')->group(function () {
        Route::get('/{language}/{level}', [QuizController::class, 'start']);
        Route::get('/{language}/{level}/{lesson}', [QuizController::class, 'start']);
        Route::post('/submit', [QuizController::class, 'submit']);
        Route::get('/{language}/{level}/results', [QuizController::class, 'results']);
    });

    // Progress tracking
    Route::prefix('progress')->group(function () {
        Route::get('/', [UserProgressController::class, 'index']);
        Route::get('/{language}', [UserProgressController::class, 'show']);
        Route::get('/{language}/{level}', [UserProgressController::class, 'level']);
        Route::post('/{language}/{level}/{section}', [UserProgressController::class, 'update']);
        Route::post('/{lesson}', [UserProgressController::class, 'update']);
    });

    // Leaderboard
    Route::prefix('leaderboard')->group(function () {
        Route::get('/', [LeaderboardController::class, 'index']);
        Route::get('/{language}', [LeaderboardController::class, 'show']);
        Route::get('/{language}/{level}', [LeaderboardController::class, 'level']);
    });

    // Achievements
    Route::prefix('achievements')->group(function () {
        Route::get('/', [AchievementController::class, 'index']);
        Route::get('/{language}', [AchievementController::class, 'show']);
    });
});