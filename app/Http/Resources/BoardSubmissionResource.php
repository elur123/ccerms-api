<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BoardCommentResource;
use App\Http\Resources\BoardResource;
use App\Http\Resources\UserResource;

class BoardSubmissionResource extends JsonResource
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
            'board' => BoardResource::make($this->whenLoaded('board')),
            'student' => UserResource::make($this->whenLoaded('student')),
            'comments' => BoardCommentResource::collection($this->whenLoaded('comments')),
            'status' => $this->whenLoaded('status'),
            'revision' => $this->revision,
            'details' => $this->details,
            'file' => $this->file,
            'file_url' => $this->file_url,
            'progress' => $this->progress,
        ];
    }
}
