<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GroupAvailablePersonnelMembers;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

use App\Models\Group;
use App\Models\GroupMember;
class GroupMemberController extends Controller
{
    
    public function store(Request $request, Group $group, GroupAvailablePersonnelMembers $gapm)
    {
        $request->validate([
            'member_id' => 'required'
        ]);

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => $request->member_id
        ]);

        return response()->json([
            'members' => UserResource::collection($group->members),
            'available' => $gapm->execute($group)   
        ], 200);
    }

    public function destroy(Request $request, Group $group, $member, GroupAvailablePersonnelMembers $gapm)
    {
        GroupMember::where('group_id', $group->id)
        ->where('user_id', $member)
        ->delete();

        return response()->json([
            'members' => UserResource::collection($group->members),
            'available' => $gapm->execute($group)
        ], 200);
    }
}
