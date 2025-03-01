<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->whenNotNull($this->description),
            'google_id' => $this->whenNotNull($this->google_id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'due_date' => $this->whenNotNull($this->due_date),
            'user' => $this->whenLoaded('user'),
            'user_id' => $this->when($this->user === null, $this->user_id),
        ];
    }
}
