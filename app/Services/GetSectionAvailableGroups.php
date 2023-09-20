<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

use App\Models\SectionGroup;
use App\Models\Group;
class GetSectionAvailableGroups
{
    public function execute($params)
    {
        $group_ids = SectionGroup::query()
        ->whereHas('section', function (Builder $query) use($params) {
            $query->where('year_end_at', $params['year_end'])
            ->where('section_type_id', $params['semester']);
        })
        ->get()
        ->pluck('group_id');

        return Group::query()
        ->whereNotIn('id', $group_ids)
        ->where('capstone_type_id', $params['capstone_type'])
        ->get()
        ->map(fn ($group) => [
            'id' => $group->id,
            'group_name' => $group->group_name
        ]);
    }
}