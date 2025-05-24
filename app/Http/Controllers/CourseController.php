<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\CourseResource;
use App\Models\Course;

class CourseController extends Controller
{
    //
    public function index()
    {
        $courses = Course::with(['modules.lessons'])->get();
        return CourseResource::collection($courses);
    }

    public function show($slug)
    {
        $course = Course::with(['modules.lessons'])->where('slug', $slug)->firstOrFail();
        return new CourseResource($course);
    }
}
