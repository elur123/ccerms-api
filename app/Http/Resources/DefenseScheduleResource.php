<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupResource;

class DefenseScheduleResource extends JsonResource
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
            'venue' => $this->venue,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'schedule_at' => $this->schedule_at,
            'type_id' => $this->type_id,
            'group_id' => $this->group_id,
            'status_id' => $this->status_id,
            'type' => $this->whenLoaded('type'),
            'status' => $this->whenLoaded('status'),
            'group' => new GroupResource($this->whenLoaded('group')),
            'panels' => $this->whenLoaded('panels'),
            'minute' => $this->whenLoaded('minute')
        ];
    }
}
