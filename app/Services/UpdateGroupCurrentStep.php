<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Group;
use App\Models\MilestoneList;
class UpdateGroupCurrentStep {

    public function execute($group_id, $step_id)
    {
        $group = Group::find($group_id);
        $group->loadCount('groupMilestone');

        $boardCount = $group->boards()
        ->where('step_id', $step_id)
        ->count();

        $boardDone = $group->boards()
        ->where('step_id', $step_id)
        ->where('progress', 100)
        ->where('status_id', StatusEnum::APPROVED->value)
        ->count();

        $list = MilestoneList::find($step_id);
        $order = $list->order_by + 1;
        $milestoneId = $list->milestone_id;

        $milestoneBoards = $group->boards()
        ->whereRelation('step', 'milestone_id', $milestoneId)
        ->get();

        $milestoneBoardCount = $milestoneBoards->count();
        $milestoneBoardProgress = $milestoneBoards->sum('progress') / $milestoneBoardCount;
        
        $group->groupMilestone()
        ->where('milestone_id', $milestoneId)
        ->update([
            'progress' => $milestoneBoardProgress
        ]);
        
        if ($boardCount == $boardDone) 
        {
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
                if ($milestoneId == $group->group_milestone_count) 
                {
                    $group->is_done = true;
                }

                $group->capstone_type_id = 2;
                $group->save();
            }
        }
        
    }
}