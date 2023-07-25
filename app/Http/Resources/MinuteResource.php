<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DefenseScheduleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\GroupResource;

class MinuteResource extends JsonResource
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
            'schedule_id' => $this->schedule_id,
            'prepared_by' => $this->prepared_by,
            'template_id' => $this->template_id,
            'group_id' => $this->group_id,
            'schedule' => new DefenseScheduleResource($this->whenLoaded('schedule')),
            'userPrepared' => new UserResource($this->whenLoaded('user')), 
            'group' => new GroupController($this->whenLoaded('group'))
        ];
    }
}
