<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Excercise;
use App\Models\UserExerciseSubmission;

class ExcerciseController extends Controller
{
    //
    public function submit($exerciseId, Request $request)
    {
        $exercise = Excercise::findOrFail($exerciseId);
        $user = auth()->user();

        // Validate the code submission
        $request->validate([
            'code' => 'required|string'
        ]);

        // Run test cases
        $testResults = $this->runTests($exercise, $request->code);

        // Create submission record
        $submission = UserExerciseSubmission::create([
            'user_id' => $user->id,
            'exercise_id' => $exercise->id,
            'code' => $request->code,
            'status' => $testResults['passed'] ? 'passed' : 'failed',
            'test_results' => $testResults['results'],
            'points_earned' => $testResults['passed'] ? $exercise->points : 0
        ]);

        return new ExerciseSubmissionResource($submission);
    }

    private function runTests($exercise, $code)
    {
        // Implement test running logic here
        // This would depend on the language and testing framework
        return [
            'passed' => true,
            'results' => []
        ];
    }
}
