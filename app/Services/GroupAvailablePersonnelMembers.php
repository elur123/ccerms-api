<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
class GroupAvailablePersonnelMembers {

    public function execute(Group $group)
    {
        return [
            'members' => $this->members(),
            'advisers' => $this->advisers($group),
            'panels' => $this->panels($group)
        ];
    }

    public function members()
    {
        $ids = GroupMember::query()
        ->get()
        ->pluck('user_id');
        
        return  User::query()
        ->approved()
        ->student()
        ->whereNotIn('id', $ids)
        ->get()
        ->map(fn($member) => [
            'id' => $member->id,
            'name' => $member->name
        ]);
    }

    public function advisers(Group $group)
    {
        $advisers = $group->advisers()
        ->pluck('user_id');

        $panels = $group->panels()
        ->pluck('user_id');

        $ids = $advisers->merge($panels)
        ->all();

        return User::query()
        ->approved()
        ->where('can_advise', true)
        ->whereNotIn('id', $ids)
        ->get()
        ->map(fn($adviser) => [
            'id' => $adviser->id,
            'name' => $adviser->name
        ]);
    }

    public function panels(Group $group)
    {
        $advisers = $group->advisers()
        ->pluck('user_id');

        $panels = $group->panels()
        ->pluck('user_id');

        $ids = $advisers->merge($panels)->all();

        return User::query()
        ->approved()
        ->where('can_panel', true)
        ->whereNotIn('id', $ids)
        ->get()
        ->map(fn($panel) => [
            'id' => $panel->id,
            'name' => $panel->name
        ]);
    }
}