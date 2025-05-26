<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'difficulty' => $this->difficulty,
            'points' => $this->points,
            'userProgress' => $this->whenLoaded('userProgress', function () {
                return UserProgressResource::collection($this->userProgress);
            })
        ];
    }
}

