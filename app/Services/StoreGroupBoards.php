<?php

namespace App\Services;

use App\Models\Group;
use App\Models\MilestoneList;
use App\Models\Board;
class StoreGroupBoards {

    public function execute(Group $group, $personnel_id, $type)
    {
        $milestones = $group->groupMilestone;

        foreach ($milestones as $key => $value) {
            $steps = MilestoneList::where('milestone_id', $value->milestone_id)
            ->when($type, function($query) use($type) {
                if ($type == 'adviser') {
                    $query->where('has_adviser', 1);
                }
                else{
                    $query->where('has_panel', 1);
                }
            })
            ->get();

            foreach ($steps as $step_key => $step_value) {
                Board::firstOrCreate(
                ['group_id' => $group->id, 'step_id' => $step_value->id, 'personnel_id' => $personnel_id],
                [
                    'status_id' => 1,
                    'type' => $type
                ]);
            }
        }
    }
}