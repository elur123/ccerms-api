<?php

namespace App\Services;

use App\Http\Resources\GroupResource;
use App\Enums\CapstoneTypeEnum;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;

use App\Models\Group;
use App\Models\User;
use App\Models\SectionGroup;
class GetGroupsByUserType {

    public function execute(User $user, $params)
    {
        $groups = [];
        $groupIds = [];
        if ($user->role_id != RoleEnum::ADMIN->value && $user->role_id != RoleEnum::RESEARCH_COORDINATOR->value) 
        {
            $groupIds = SectionGroup::query()
            ->whereRelation('section', 'user_id', $user->id)
            ->get()
            ->pluck('group_id')
            ->unique();
        }

        $groups = Group::query()
        ->with('advisers', 'panels', 'groupMilestone')
        ->when(count($groupIds) > 0, function($query) use($groupIds) {
            $query->whereIn('id', $groupIds);
        })
        ->when($params['capstoneType'], function($query) use($params) {
            if ($params['capstoneType'] == CapstoneTypeEnum::ONE->value && $params['status'] == StatusEnum::DONE->value) {
                $query->where('capstone_type_id', CapstoneTypeEnum::TWO->value)
                ->where('is_done', 0);
            }
            else if ($params['capstoneType'] == CapstoneTypeEnum::TWO->value && $params['status'] == StatusEnum::DONE->value) {
                $query->where('capstone_type_id', CapstoneTypeEnum::TWO->value)
                ->where('is_done', 1);
            }
            else {
                $query->where('capstone_type_id', $params['capstoneType']);
            }
        })
        ->get();

        return GroupResource::collection($groups);
    }
}