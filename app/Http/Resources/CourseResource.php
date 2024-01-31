<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\MilestoneResource;
class CourseResource extends JsonResource
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
            'key' => $this->key,
            'label' => $this->label,
            'milestone_one' => $this->milestone_one,
            'milestone_two' => $this->milestone_two,
            'milestoneOne' => MilestoneResource::make($this->whenLoaded('milestoneOne')),
            'milestoneTwo' => MilestoneResource::make($this->whenLoaded('milestoneTwo')),
        ];
    }
}
