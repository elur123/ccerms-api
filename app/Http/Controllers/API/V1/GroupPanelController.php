<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GroupAvailablePersonnelMembers;
use App\Services\StoreGroupBoards;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

use App\Models\Group;
use App\Models\GroupPanel;
class GroupPanelController extends Controller
{
    public function store(Request $request, Group $group, GroupAvailablePersonnelMembers $gapm, StoreGroupBoards $boards)
    {
        $request->validate([
            'panel_id' => 'required'
        ]);

        GroupPanel::create([
            'group_id' => $group->id,
            'user_id' => $request->panel_id
        ]); 

        $boards->execute($group, $request->panel_id, 'panel');

        return response()->json([
            'panels' => UserResource::collection($group->panels),
            'available' => $gapm->execute($group)
        ], 200);
    }

    public function destroy(Request $request, Group $group, $panel, GroupAvailablePersonnelMembers $gapm)
    {
        GroupPanel::where('group_id', $group->id)
        ->where('user_id', $panel)
        ->delete();

        return response()->json([
            'panels' => UserResource::collection($group->panels),
            'available' => $gapm->execute($group)
        ], 200);
    }
}
