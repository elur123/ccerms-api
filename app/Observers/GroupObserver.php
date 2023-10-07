<?php

namespace App\Observers;

use App\Enums\CapstoneTypeEnum;
use App\Models\Group;
class GroupObserver
{
    /**
     * Handle the Group "created" event.
     */
    public function creating(Group $group): void
    {
        $group->key = generateGroupKey();
        $group->capstone_type_id = CapstoneTypeEnum::ONE->value;
    }
    
    /**
     * Handle the Group "created" event.
     */

    public function created(Group $group): void
    {
        $course = $group->course;

        $currentOneMilestone = $course->milestoneOne
        ->milestoneList()
        ->firstWhere('order_by', 1);

        $currentTwoMilestone = $course->milestoneOne
        ->milestoneList()
        ->firstWhere('order_by', 1);

        $group->groupMilestone()->create([
            'milestone_id' => $course->milestone_one,
            'milestone_list_id' => $currentOneMilestone->id,
            'capstone_type_id' => CapstoneTypeEnum::ONE->value,
        ]);

        $group->groupMilestone()->create([
            'milestone_id' => $course->milestone_two,
            'milestone_list_id' => $currentTwoMilestone->id,
            'capstone_type_id' => CapstoneTypeEnum::TWO->value,
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
