<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

use App\Models\Group;
use App\Models\GroupAdviser;
class GroupAdviserController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $request->validate([
            'adviser_ids' => 'required|array'
        ]);

        $group->advisers()->sync($request->adviser_ids);

        return response()->json([
            'advisers' => UserResource::collection($group->advisers)
        ], 200);
    }

    public function destroy(Request $request, Group $group, $adviser)
    {
        GroupAdviser::where('group_id', $group->id)
        ->where('user_id', $adviser)
        ->delete();

        return response()->json([
            'advisers' => UserResource::collection($group->advisers)
        ], 200);
    }
}
