<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\MilestoneResource;
class MilestoneListResource extends JsonResource
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
            'description' => $this->description,
            'percent' => $this->percent,
            'milestone_id' => $this->milestone_id,
            'milestone' => new MilestoneResource($this->whenLoaded('milestone'))
        ];
    }
}
