<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\GroupResource;

class StudentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => UserResource::make($this->whenLoaded('user')),
            'course' => CourseResource::make($this->whenLoaded('course')),
            'groupMember' =>  $this->whenLoaded('groupMember')
        ];
    }
}
