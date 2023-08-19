<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MilestoneListResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\BoardSubmissionResource;

class BoardResource extends JsonResource
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
            'group' => GroupResource::make($this->whenLoaded('group')),
            'step' => MilestoneListResource::make($this->whenLoaded('step')),
            'personnel' => UserResource::make($this->whenLoaded('personnel')),
            'submissions' => BoardSubmissionResource::collection($this->whenLoaded('submissions')),
            'status' => $this->whenLoaded('status'),
            'progress' => $this->progress,
            'type' => $this->type
        ];
    }
}
