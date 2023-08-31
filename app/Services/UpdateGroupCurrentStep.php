<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Group;
use App\Models\MilestoneList;
class UpdateGroupCurrentStep {

    public function execute($group_id, $step_id)
    {
        $group = Group::find($group_id);

        $boardCount = $group->boards()
        ->where('step_id', $step_id)
        ->count();

        $boardDone = $group->boards()
        ->where('step_id', $step_id)
        ->where('progress', 100)
        ->count();
        
        if ($boardCount == $boardDone) 
        {
            $list = MilestoneList::find($step_id);

            $order = $list->order_by + 1;
            $milestoneId = $list->milestone_id;

            $findNext = MilestoneList::where('milestone_id', $milestoneId)
            ->where('order_by', $order)
            ->first();

            if ($findNext != null) 
            {
                $group->groupMilestone()
                ->where('milestone_id', $milestoneId)
                ->update([
                    'milestone_list_id' => $findNext->id
                ]);
            }
            else
            {
                $group->capstone_type_id = 2;
                $group->save();
            }
        }
        
    }
}