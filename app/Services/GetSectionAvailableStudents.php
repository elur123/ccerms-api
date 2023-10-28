<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

use App\Models\SectionStudent;
use App\Models\User;
class GetSectionAvailableStudents
{

    public function execute($params)
    {
        $student_ids = SectionStudent::query()
        ->whereHas('section', function (Builder $query) use($params) {
            $query->where('year_end_at', $params['year_end'])
            ->where('section_type_id', $params['semester']);
        })
        ->get()
        ->pluck('user_id');

        return User::query()
        ->student()
        // ->approved()
        ->whereNotIn('id', $student_ids)
        ->get()
        ->map(fn ($student) => [
            'id' => $student->id,
            'name' => $student->name
        ]);
    }
}