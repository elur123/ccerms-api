<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;

use App\Models\Board;
use App\Models\User;
use App\Models\GroupMember;
class GetBoardRecipientSubmission {

    public function execute($params)
    {
        $user_id = request()->user()->id;

        $personnels = Board::where('group_id', $params['group_id'])
        ->where('step_id', $params['step_id'])
        ->where('personnel_id', '!=', $user_id)
        ->get()
        ->map(fn($user) => [
            'id' => $user->personnel_id,
            'role' => 'admin'
        ]); 

        $adminUsers = User::query()
        ->approved()
        ->where('id', '!=', $user_id)
        ->whereIn('role_id', [RoleEnum::ADMIN->value, RoleEnum::RESEARCH_COORDINATOR->value])
        ->get()
        ->map(fn($user) => [
            'id' => $user->id,
            'role' => 'admin'
        ]); 

        $groupMembers = GroupMember::query()
        ->where('group_id', $params['group_id'])
        ->where('user_id', '!=', $user_id)
        ->get()
        ->map(fn($user) => [
            'id' => $user->user_id,
            'role' => 'student'
        ]); 
        
        $recipients = $personnels->merge($adminUsers);
        $recipients = $recipients->merge($groupMembers);

        return $recipients->unique();
    }
}