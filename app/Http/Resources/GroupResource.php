<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\CapstoneTypeEnum;

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
            'progress' => $this->whenLoaded('groupMilestone', function() {
                return $this->whenLoaded('groupMilestone')->sum('progress') / $this->whenLoaded('groupMilestone')->count();
            }),
            'oneProgress' => $this->whenLoaded('groupMilestone', function() {
                return $this->whenLoaded('groupMilestone')
                ->where('capstone_type_id', CapstoneTypeEnum::ONE->value)
                ->pluck('progress')
                ->first();
            }),
            'twoProgress' => $this->whenLoaded('groupMilestone', function() {
                return $this->whenLoaded('groupMilestone')
                ->where('capstone_type_id', CapstoneTypeEnum::TWO->value)
                ->pluck('progress')
                ->first();
            }),
            'course' => CourseResource::make($this->whenLoaded('course')),
            'capstoneType' => CapstoneTypeResource::make($this->whenLoaded('capstoneType')),
            'milestones' => $this->customGroupMilestone,
            'members' => UserResource::collection($this->whenLoaded('members')),
            'advisers' => UserResource::collection($this->whenLoaded('advisers')),
            'panels' => UserResource::collection($this->whenLoaded('panels')),
            'statisticians' => UserResource::collection($this->whenLoaded('statisticians'))
        ];
    }
}
