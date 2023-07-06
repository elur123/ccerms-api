<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StudentDetailResource;
use App\Enums\RoleEnum;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'mobile_number' => $this->mobile_number,
            'role_id' => $this->role_id,
            'status_id' => $this->status_id,
            'studentDetails' => new StudentDetailResource($this->whenLoaded('studentDetails')),
            'status' => $this->whenLoaded('status'),
            'role' => $this->whenLoaded('role'),
            'can_advise' => $this->when($this->role_id !== RoleEnum::STUDENT->value, (boolean)$this->can_advise),
            'can_panel' => $this->when($this->role_id !== RoleEnum::STUDENT->value, (boolean)$this->can_panel),
            'can_teach' => $this->when($this->role_id !== RoleEnum::STUDENT->value, (boolean)$this->can_teach),
        ];
    }
}
