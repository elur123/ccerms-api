<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

use App\Models\Group;
use App\Models\GroupPanel;
class GroupPanelController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $request->validate([
            'panel_ids' => 'required|array'
        ]);

        $group->panels()->sync($request->panel_ids);

        return response()->json([
            'panels' => UserResource::collection($group->panels)
        ], 200);
    }

    public function destroy(Request $request, Group $group, $panel)
    {
        GroupPanel::where('group_id', $group->id)
        ->where('user_id', $panel)
        ->delete();

        return response()->json([
            'panels' => UserResource::collection($group->panels)
        ], 200);
    }
}
