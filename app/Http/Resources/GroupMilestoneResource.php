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
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'milestone_id' => $this->milestone_id,
            'milestone_list_id' => $this->milestone_list_id,
            'group' => new GroupResource($this->whenLoaded('group')),
            'milestone' => new MilestoneResource($this->whenLoaded('milestone')),
            'milestoneList' => new MilestoneListResource($this->whenLoaded('milestoneList'))
        ];
    }
}
