<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise; // Fixed spelling
use App\Models\UserExerciseSubmission;
use App\Http\Resources\ExerciseSubmissionResource;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller // Fixed spelling
{
    public function submit($exerciseId, Request $request)
    {
        $exercise = Exercise::findOrFail($exerciseId); // Fixed spelling
        $user = Auth::user();

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
        $testCases = $exercise->test_cases ?? [];
        $results = [];
        $allPassed = true;

        foreach ($testCases as $testCase) {
            try {
                // Basic CSS validation - you can expand this
                $result = $this->validateCssCode($code, $testCase);

                $results[] = [
                    'test_case' => $testCase,
                    'passed' => $result['passed'],
                    'expected' => $testCase['expected_output'] ?? null,
                    'actual' => $result['output'] ?? null,
                    'error' => $result['error'] ?? null
                ];

                if (!$result['passed']) {
                    $allPassed = false;
                }
            } catch (\Exception $e) {
                $results[] = [
                    'test_case' => $testCase,
                    'passed' => false,
                    'error' => $e->getMessage()
                ];
                $allPassed = false;
            }
        }

        return [
            'passed' => $allPassed,
            'results' => $results
        ];
    }

    private function validateCssCode($code, $testCase)
    {
        // Simple CSS validation - you can make this more sophisticated
        $input = $testCase['input'] ?? '';
        $expectedOutput = $testCase['expected_output'] ?? true;

        // Basic check if the submitted code contains expected patterns
        $passed = true;
        $error = null;

        try {
            // Remove comments and whitespace for comparison
            $cleanCode = preg_replace('/\/\*.*?\*\//', '', $code);
            $cleanInput = preg_replace('/\/\*.*?\*\//', '', $input);

            // Simple pattern matching - you can enhance this
            if (trim($cleanCode) === trim($cleanInput)) {
                $passed = true;
            } else {
                $passed = false;
                $error = 'Code does not match expected pattern';
            }
        } catch (\Exception $e) {
            $passed = false;
            $error = $e->getMessage();
        }

        return [
            'passed' => $passed,
            'output' => $expectedOutput,
            'error' => $error
        ];
    }
}