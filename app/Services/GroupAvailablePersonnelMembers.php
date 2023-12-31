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
            'members' => $this->members($group),
            'advisers' => $this->advisers($group),
            'panels' => $this->panels($group),
            'statisticians' => $this->statisticians($group)
        ];
    }

    public function members($group)
    {
        $ids = GroupMember::query()
        ->get()
        ->pluck('user_id');
        
        return  User::query()
        ->select('users.*', 'student_details.user_id', 'student_details.course_id')
        ->leftJoin('student_details', 'student_details.user_id', 'users.id')
        ->approved()
        ->student()
        ->whereNotIn('users.id', $ids)
        ->where('student_details.course_id', $group->course_id)
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

    public function statisticians(Group $group)
    {
        $advisers = $group->advisers()
        ->pluck('user_id');

        $panels = $group->panels()
        ->pluck('user_id');

        $statisticians = $group->statisticians()
        ->pluck('user_id');

        $ids = $advisers->merge($panels);
        $ids = $ids->merge($statisticians)->all();

        return User::query()
        ->approved()
        ->where('can_stat', true)
        ->whereNotIn('id', $ids)
        ->get()
        ->map(fn($stat) => [
            'id' => $stat->id,
            'name' => $stat->name
        ]);
    }
}