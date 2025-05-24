<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'difficulty' => $this->difficulty,
            'estimatedTime' => $this->estimated_time,
            'lessonsCount' => $this->lessons_count,
            'exercisesCount' => $this->exercises_count,
            'projectsCount' => $this->projects_count,
            'rating' => $this->rating,
            'modules' => ModuleResource::collection($this->whenLoaded('modules')),
            'userProgress' => $this->whenLoaded('userProgress', function () {
                return UserProgressResource::collection($this->userProgress);
            })
        ];
    }
}
