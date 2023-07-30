<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchArchiveResource extends JsonResource
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
            'group_name' => $this->group_name,
            'title' => $this->title, 
            'file' => $this->research_file,
            'file_url' => config('app.url') .'/'. str_replace('public', 'storage', $this->file_url),
            'keywords' => $this->keywords,
            'section_year_from' => $this->section_year_from,
            'section_year_to' => $this->section_year_to,
            'adviser' => $this->adviser,
            'course' => $this->whenLoaded('course'),
            'members' => $this->whenLoaded('members'),
        ];
    }
}
