<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\GroupResource;
use App\Http\Resources\MilestoneResource;
use App\Http\Resources\MilestoneListResource;
class GroupMilestoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection();
        // return [
        //     'id' => $this->id,
        //     'group_id' => $this->group_id,
        //     'progress' => $this->progress,
        //     'is_open' => $this->is_open,
        //     'milestone_id' => $this->milestone_id,
        //     'milestone_list_id' => $this->milestone_list_id,
        //     'group' => GroupResource::make($this->whenLoaded('group')),
        //     'milestone' => MilestoneResource::make($this->whenLoaded('milestone')),
        //     'currentMilestone' => MilestoneListResource::make($this->whenLoaded('currentMilestone')),
        //     'latestEndorsed' => MilestoneResource::make($this->whenLoaded('endorseMilestone')),
        //     'capstone_type_id' => $this->capstone_type_id
        // ];
    }
}
