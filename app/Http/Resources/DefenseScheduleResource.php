<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MilestoneListResource;

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
            'start_at' => date('H:i', strtotime($this->start_at)),
            'end_at' => date('H:i', strtotime($this->end_at)),
            'schedule_at' => date('Y-m-d', strtotime($this->start_at)),
            'type_id' => $this->type_id,
            'group_id' => $this->group_id,
            'status_id' => $this->status_id,
            'type' => $this->whenLoaded('type'),
            'status' => $this->whenLoaded('status'),
            'group' => GroupResource::make($this->whenLoaded('group')),
            'panels' => $this->whenLoaded('panels'),
            'minute' => $this->whenLoaded('minute'),
            'step' => MilestoneListResource::make($this->whenLoaded('step'))
        ];
    }
}
