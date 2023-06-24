<?php

namespace App\Observers;

use App\Models\Group;
class GroupObserver
{
    /**
     * Handle the Group "created" event.
     */
    public function created(Group $group): void
    {
        $course = $group->course;

        $group->groupMilestone()->create([
            'milestone_id' => $course->milestone_one,
            'milestone_list_id' => 2
        ]);

        $group->groupMilestone()->create([
            'milestone_id' => $course->milestone_two,
            'milestone_list_id' => 3
        ]);
    }

    /**
     * Handle the Group "updated" event.
     */
    public function updated(Group $group): void
    {
        //
    }

}
