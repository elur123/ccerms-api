<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BoardSubmissionResource;
use App\Http\Resources\UserResource;
class BoardCommentResource extends JsonResource
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
            'submission' => BoardSubmissionResource::make($this->whenLoaded('submission')),
            'commentedBy' => UserResource::make($this->whenLoaded('commentedBy')),
            'comment' => $this->comment,
            'file' => $this->file,
            'file_url' => $this->file_url
        ];
    }
}
