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
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if ($user->role_id === RoleEnum::STUDENT->value) 
        {
            $user->studentDetails()->create([
                'course_id' => request()->course_id
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

}
