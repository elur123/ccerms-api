<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Services\GroupAvailablePersonnelMembers;
use App\Services\ValidateCourseMilestone;

use App\Models\Group;
class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::query()
        ->with('course', 'capstoneType', 'groupMilestone', 'members', 'panels')
        ->get();

        return response()->json([
            'groups' => GroupResource::collection($groups)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupRequest $request, ValidateCourseMilestone $validateCourse)
    {
        $validate = $validateCourse->execute($request->course_id);

        if (!$validate) {
            return response()->json([
                'message' => 'Setup first milestone list!'
            ], 422);
        }

        Group::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, GroupAvailablePersonnelMembers $gapm)
    {
        $group->load('course', 'capstoneType', 'groupMilestone.currentMilestone', 'groupMilestone.milestone.milestoneList', 'members', 'advisers', 'panels', 'statisticians');

        $users_available = $gapm->execute($group);

        return response()->json([
            'group' => new GroupResource($group),
            'members' => $users_available['members'],
            'advisers' => $users_available['advisers'],
            'panels' => $users_available['panels'],
            'statisticians' => $users_available['statisticians']
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupRequest $request, Group $group)
    {
        $group->update($request->validated());

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return $this->index();
    }
}
