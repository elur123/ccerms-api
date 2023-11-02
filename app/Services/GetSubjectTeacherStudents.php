<?php

namespace App\Services;

use App\Models\SectionStudent;
class GetSubjectTeacherStudents {

    public function execute($teacher_id)
    {
        $students = SectionStudent::query()
        ->whereRelation('section', 'user_id', $teacher_id)
        ->get()
        ->pluck('user_id')
        ->unique();

        return $students;
    }
}