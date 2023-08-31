<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Board;
use App\Models\MilestoneList;
class GetGroupBoards {

    public function execute($group_id, $step_id)
    {
        $milestoneList = MilestoneList::find($step_id);

        $adviserBoard = Board::query()
        ->where('group_id', $group_id)
        ->where('step_id', $step_id)
        ->where('type', 'adviser')
        ->first();

        $adviserDone = $adviserBoard !== null && $adviserBoard->status_id === StatusEnum::APPROVED->value;

        return Board::query()
        ->with('personnel', 'submissions.status', 'submissions.student', 'submissions.comments.user')
        ->where('group_id', $group_id)
        ->where('step_id', $step_id)
        ->when($milestoneList->adviser_first, function($query) use($adviserDone) {
            if (!$adviserDone) {
                $query->where('type', 'adviser');
            }
        })
        ->get();
    }
}