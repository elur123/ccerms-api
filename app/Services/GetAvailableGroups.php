<?php

namespace App\Services;

use App\Models\Group;
class GetAvailableGroups {

    public function execute()
    {
        return Group::query()
        ->with('groupMilestone.milestone.milestoneList')
        ->where('is_done', 0)
        ->get()
        ->map(function($group) {
            $capstoneType = $group->capstone_type_id - 1;
            $milestones = $group->groupMilestone[$capstoneType];
            $milestoneLists = $milestones->milestone->milestoneList;

            return [
                'id' => $group->id,
                'group_name' => $group->group_name,
                'milestoneLists' => $milestoneLists
            ];
        });
    }
}