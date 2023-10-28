<?php

namespace App\Services;

use App\Models\Course;
class ValidateCourseMilestone {

    public function execute($course_id)
    {
        $validate = true;
        $course = Course::find($course_id);
        $course->load('milestoneOne', 'milestoneTwo');

        $milestoneOneList =  $course->milestoneOne->milestoneList()
        ->where('order_by', 1)
        ->first();

        $milestoneTwoList =  $course->milestoneTwo->milestoneList()
        ->where('order_by', 1)
        ->first();

        if ($milestoneOneList == null || $milestoneTwoList == null) {
            $validate = false;
        }

        return $validate;
    }
}