<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CourseResource;
use App\Http\Resources\CapstoneTypeResource;
use App\Http\Resources\GroupMilestoneResource;
use App\Http\Resources\UserResource;
class GroupResource extends JsonResource
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
            'group_name' => $this->group_name,
            'title' => $this->title,
            'isDone' => $this->is_done, 
            'course_id' => $this->course_id,
            'capstone_type_id' => $this->capstone_type_id,
            'course' => new CourseResource($this->whenLoaded('course')),
            'capstoneType' => new CapstoneTypeResource($this->whenLoaded('capstoneType')),
            'milestones' => GroupMilestoneResource::collection($this->whenLoaded('groupMilestone')),
            'members' => UserResource::collection($this->whenLoaded('members')),
            'advisers' => UserResource::collection($this->whenLoaded('advisers')),
            'panels' => UserResource::collection($this->whenLoaded('panels'))
        ];
    }
}
