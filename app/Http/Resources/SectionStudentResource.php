<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\SectionResource;

class SectionStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student' => UserResource::make($this->whenLoaded('student')),
            'section' => SectionResource::make($this->whenLoaded('section')),
            'status' => $this->whenLoaded('status')
        ];
    }
}
