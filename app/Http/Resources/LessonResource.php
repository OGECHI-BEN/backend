<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => json_decode($this->content, true), // Ensure content is decoded
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
                    // Include the is_completed accessor from the Question model
                    return [
                        'id' => $question->id,
                        'type' => $question->type,
                        'question_text' => $question->question_text,
                        'options' => $question->options,
                        'points' => $question->points,
                        'is_completed' => (bool) $question->is_completed, // Include is_completed
                    ];
                });
            }),
            'exercises' => $this->whenLoaded('exercises', function () {
                return $this->exercises->map(function ($exercise) {
                    // Include the is_completed accessor from the Exercise model
                    return [
                        'id' => $exercise->id,
                        'title' => $exercise->title,
                        'description' => $exercise->description,
                        'type' => $exercise->type,
                        'is_completed' => (bool) $exercise->is_completed, // Include is_completed
                    ];
                });
            }),
            'user_progress' => $this->whenLoaded('userProgress', function () {
                return [
                    'completed' => (bool)($this->userProgress->status === 'completed'), // Assuming status determines completion
                    'completed_at' => $this->userProgress->completed_at ?? null,
                    'score' => $this->userProgress->score ?? 0,
                ];
            }),
            'created_at' => $this->created_at, // Ensure these are still included if needed
            'updated_at' => $this->updated_at, // Ensure these are still included if needed
        ];
    }
}
