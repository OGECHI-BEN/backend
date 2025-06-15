<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Removed: use Illuminate\Support\Facades\Storage; // No longer needed for this approach

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $timeFrame = $request->input('time_frame', 'all');
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = Users::select([
            'users.id',
            'users.username as name',
            'users.avatar', // Select the raw avatar path from the database
            'users.points',
            DB::raw('COUNT(DISTINCT user_progress.id) as completed_lessons')
        ])
        ->leftJoin('user_progress', 'users.id', '=', 'user_progress.user_id')
        ->where('user_progress.status', 'completed');

        // Apply time frame filter
        if ($timeFrame !== 'all') {
            $date = now();
            switch ($timeFrame) {
                case 'week':
                    $date = $date->subWeek();
                    break;
                case 'month':
                    $date = $date->subMonth();
                    break;
            }
            $query->where('user_progress.completed_at', '>=', $date);
        }

        $leaderboard = $query->groupBy('users.id')
            ->orderBy('users.points', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // No transformation of avatar URL here. Frontend will handle it.
        // If your database *stores* full URLs already (e.g., external image links),
        // then no change is needed here. If it stores just filenames, then this is correct.
        return response()->json([
            'data' => $leaderboard->items(),
            'total' => $leaderboard->total(),
            'current_page' => $leaderboard->currentPage(),
            'per_page' => $leaderboard->perPage(),
            'last_page' => $leaderboard->lastPage()
        ]);
    }

    public function userStats($userId)
    {
        $user = Users::findOrFail($userId);
        
        $stats = [
            'points' => $user->points,
            'rank' => Users::where('points', '>', $user->points)->count() + 1,
            'completedLessons' => $user->progress()
                ->where('status', 'completed')
                ->count(),
            'badges' => 0
        ];

        return response()->json($stats);
    }
}