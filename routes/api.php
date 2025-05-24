<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExcerciseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CourseController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // Course routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{slug}', [CourseController::class, 'show']);

    // Lesson routes
    Route::get('/courses/{courseSlug}/lessons/{lessonId}', [LessonController::class, 'show']);
    Route::post('/lessons/{lessonId}/complete', [LessonController::class, 'complete']);

    // Exercise routes
    Route::post('/exercises/{exerciseId}/submit', [ExcerciseController::class, 'submit']);
});