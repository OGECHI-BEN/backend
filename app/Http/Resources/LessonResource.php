<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'order' => $this->order,
            'duration' => $this->duration,
            'exercises' => ExerciseResource::collection($this->whenLoaded('exercises')),
            'userProgress' => $this->whenLoaded('userProgress', function () {
                return UserProgressResource::collection($this->userProgress);
            })
        ];
    }
}
