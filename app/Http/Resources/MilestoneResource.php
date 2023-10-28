<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CapstoneTypeResource;
use App\Http\Resources\MilestoneListResource;
class MilestoneResource extends JsonResource
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
            'capstone_type_id' => $this->capstone_type_id,
            'capstoneType' => new CapstoneTypeResource($this->whenLoaded('capstoneType')),
            'milestonList' => MilestoneListResource::collection($this->whenLoaded('milestoneList'))
        ];
    }
}
