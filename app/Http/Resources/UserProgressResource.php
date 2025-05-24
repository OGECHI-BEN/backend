<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'progressableId' => $this->progressable_id,
            'progressableType' => $this->progressable_type,
            'status' => $this->status,
            'completedAt' => $this->completed_at,
            'score' => $this->score,
            'lastAccessedAt' => $this->last_accessed_at
        ];
    }
}
