<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achievement;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::all();
        return response()->json($achievements);
    }

    public function show($language)
    {
        $achievements = Achievement::where('language', $language)->get();
        return response()->json($achievements);
    }
}
