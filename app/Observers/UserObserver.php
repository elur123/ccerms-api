<?php

namespace App\Observers;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Models\User;
class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        $user->status_id = StatusEnum::PENDING->value;

        switch ($user->role_id) {
            case RoleEnum::ADVISER->value:
                $user->can_advise = true;
                break;

            case RoleEnum::PANEL->value:
                $user->can_panel = true;
                break;

            case RoleEnum::SUBJECT_TEACHER->value:
                $user->can_teach = true;
                break;

            case RoleEnum::RESEARCH_COORDINATOR->value: 
            case RoleEnum::ADMIN->value:
                $user->can_panel = true;
                $user->can_advise = true;
                $user->can_teach = true;
                break;
            
            default:
                break;
        }
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if ($user->role_id === RoleEnum::STUDENT->value) 
        {
            $user->studentDetails()->create([
                'student_id' => request()->student_id,
                'course_id' => request()->course_id
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        
    }

}
