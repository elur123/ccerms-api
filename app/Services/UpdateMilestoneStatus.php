<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\DefenseSchedule;
class UpdateMilestoneStatus {

    public function execute(DefenseSchedule $defense)
    {
        $stepsCount = $defense->group->boards()
        ->where('step_id', $defense->step_id)
        ->where('progress', '<', 100)
        ->count(); 

        if ($stepsCount > 0) {
            $defense->group->groupMilestone()
            ->where('capstone_type_id', $defense->group->capstone_type_id)
            ->update([
                'is_open' => false
            ]);

            $defense->update([
                'status_id' => StatusEnum::RESCHED->value
            ]);
        }
    }
}