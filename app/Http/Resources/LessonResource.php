<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'order' => $this->order,
            'estimated_time' => $this->estimated_time,
            'language' => [
                'id' => $this->language->id,
                'name' => $this->language->name,
                'slug' => $this->language->slug,
            ],
            'level' => [
                'id' => $this->level->id,
                'name' => $this->level->name,
                'slug' => $this->level->slug,
            ],
            'questions' => $this->whenLoaded('questions', function () {
                return $this->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'type' => $question->type,
                        'question_text' => $question->question_text,
                        'options' => $question->options,
                        'points' => $question->points,
                    ];
                });
            }),
            'exercises' => $this->whenLoaded('exercises', function () {
                return $this->exercises->map(function ($exercise) {
                    return [
                        'id' => $exercise->id,
                        'title' => $exercise->title,
                        'description' => $exercise->description,
                        'type' => $exercise->type,
                    ];
                });
            }),
            'user_progress' => $this->whenLoaded('userProgress', function () {
                return [
                    'completed' => $this->userProgress->completed ?? false,
                    'completed_at' => $this->userProgress->completed_at ?? null,
                    'score' => $this->userProgress->score ?? 0,
                ];
            }),
        ];
    }
}
