<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SectionStudentResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

class SectionResource extends JsonResource
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
            'section_code' => $this->section_code,
            'room_number' => $this->room_number,
            'time_start_at' => $this->time_start_at,
            'time_end_at' => $this->time_end_at,
            'year_start_at' => $this->year_start_at,
            'year_end_at' => $this->year_end_at,
            'start_at' => Carbon::parse($this->start_at)->format('M d, Y'),
            'end_at' => Carbon::parse($this->end_at)->format('M d, Y'),
            'section_type_id' => $this->section_type_id,
            'user_id' => $this->user_id,
            'section_type' => $this->whenLoaded('sectionType'),
            'teacher' => new UserResource($this->whenLoaded('teacher')),
            'students' => SectionStudentResource::collection($this->whenLoaded('sectionStudent')),
            'groups' => GroupResource::collection($this->whenLoaded('groups'))
        ];
    }
}
