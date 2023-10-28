<?php

namespace App\Services;

use App\Enums\RoleEnum;

use App\Models\Section;
use App\Models\User;
class GetSectionsByUserType {

    public function execute(User $user, $params)
    {
        $sections = Section::query()
        ->with('sectionStudent.student.studentDetails.course', 'sectionStudent.status', 'teacher', 'groups', 'status')
        ->when($user->role_id != RoleEnum::ADMIN->value && $user->role_id != RoleEnum::RESEARCH_COORDINATOR->value, function($query) use($user) {
            $query->where('user_id', $user->id);
        })
        ->when($params['startAt'] && $params['endAt'], function($query) use($params) {
            $query->where('year_start_at', '>=', $params['startAt'])
            ->where('year_end_at', '<=', $params['endAt']);
        })
        ->get();

        return $sections;
    }
}