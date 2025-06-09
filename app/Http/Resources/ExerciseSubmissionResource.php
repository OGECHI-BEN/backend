<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseSubmissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'exercise_id' => $this->exercise_id,
            'code' => $this->code,
            'status' => $this->status,
            'test_results' => $this->test_results,
            'points_earned' => $this->points_earned,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
