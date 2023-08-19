<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GroupAvailablePersonnelMembers;
use App\Services\StoreGroupBoards;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

use App\Models\Group;
use App\Models\GroupAdviser;
class GroupAdviserController extends Controller
{
    public function store(Request $request, Group $group, GroupAvailablePersonnelMembers $gapm, StoreGroupBoards $boards)
    {
        $request->validate([
            'adviser_id' => 'required'
        ]);

        GroupAdviser::create([
            'group_id' => $group->id,
            'user_id' => $request->adviser_id
        ]);

        $boards->execute($group, $request->adviser_id, 'adviser');

        return response()->json([
            'advisers' => UserResource::collection($group->advisers),
            'available' => $gapm->execute($group)
        ], 200);
    }

    public function destroy(Request $request, Group $group, $adviser, GroupAvailablePersonnelMembers $gapm)
    {
        GroupAdviser::where('group_id', $group->id)
        ->where('user_id', $adviser)
        ->delete();

        return response()->json([
            'advisers' => UserResource::collection($group->advisers),
            'available' => $gapm->execute($group)
        ], 200);
    }
}
