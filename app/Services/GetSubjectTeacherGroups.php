<?php

namespace App\Services;

use App\Models\SectionGroup;
class GetSubjectTeacherGroups {

    public function execute($teacher_id)
    {
        $groups = SectionGroup::query()
        ->whereRelation('section', 'user_id', $teacher_id)
        ->get()
        ->pluck('group_id')
        ->unique();

        return $groups;
    }
}