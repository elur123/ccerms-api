<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

use App\Models\Group;
use App\Models\GroupMember;
class GroupMemberController extends Controller
{
    
    public function store(Request $request, Group $group)
    {
        $request->validate([
            'member_ids' => 'required|array'
        ]);

        $group->members()->sync($request->member_ids);

        return response()->json([
            'members' => UserResource::collection($group->members)
        ], 200);
    }

    public function destroy(Request $request, Group $group, $member)
    {
        GroupMember::where('group_id', $group->id)
        ->where('user_id', $member)
        ->delete();

        return response()->json([
            'members' => UserResource::collection($group->members)
        ], 200);
    }
}
