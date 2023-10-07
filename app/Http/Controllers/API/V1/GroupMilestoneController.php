<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GroupMilestone;
class GroupMilestoneController extends Controller
{
    
    public function update(Request $request, GroupMilestone $groupmilestone)
    {
        $request->validate([
            'milestone_id' => 'required',
            'milestone_list_id' => 'required'
        ]);

        $groupmilestone->update($request->all());

        return $groupmilestone;
    }

    public function updateStatus(Request $request, GroupMilestone $groupmilestone)
    {
        $groupmilestone->update([
            'is_open' => $request->is_open
        ]);

        return $groupmilestone;
    }
}
