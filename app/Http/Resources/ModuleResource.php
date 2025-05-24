<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),
            'userProgress' => $this->whenLoaded('userProgress', function () {
                return UserProgressResource::collection($this->userProgress);
            })
        ];
    }
}
