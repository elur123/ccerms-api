<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GroupAvailablePersonnelMembers;
use App\Services\StoreGroupBoards;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

use App\Models\Group;
use App\Models\GroupStatistician;
class GroupStatisticianController extends Controller
{
    public function store(Request $request, Group $group, GroupAvailablePersonnelMembers $gapm, StoreGroupBoards $boards)
    {
        $request->validate([
            'statistician_id' => 'required'
        ]);

        GroupStatistician::create([
            'group_id' => $group->id,
            'user_id' => $request->statistician_id
        ]); 

        $boards->execute($group, $request->statistician_id, 'statistician');

        return response()->json([
            'statisticians' => UserResource::collection($group->statisticians),
            'available' => $gapm->execute($group)
        ], 200);
    }

    public function destroy(Request $request, Group $group, $panel, GroupAvailablePersonnelMembers $gapm)
    {
        GroupStatistician::where('group_id', $group->id)
        ->where('user_id', $panel)
        ->delete();

        return response()->json([
            'statisticians' => UserResource::collection($group->statisticians),
            'available' => $gapm->execute($group)
        ], 200);
    }
}
