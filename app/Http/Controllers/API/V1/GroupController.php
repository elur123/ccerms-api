<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Services\GroupAvailablePersonnelMembers;
use App\Services\ValidateCourseMilestone;
use App\Services\GetSubjectTeacherGroups;
use App\Enums\RoleEnum;

use App\Models\Group;
use App\Models\GroupAdviser;
use App\Models\GroupPanel;
class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $stGroups = new GetSubjectTeacherGroups();

        $groups = Group::query()
        ->with('course', 'capstoneType', 'groupMilestone', 'members', 'panels')
        ->filter(request()->s)
        ->when(request()->orderBy && request()->orderFunction, function ($query) {
            if (request()->orderBy == 'course') 
            {
                $query->orderBy('courses.label', request()->orderFunction);
            }
            else
            {
                $query->orderBy(request()->orderBy, request()->orderFunction);
            }
            
        })
        ->when(request()->user()->role_id, function($query) use($stGroups) {
            switch (request()->user()->role_id) {
                case RoleEnum::SUBJECT_TEACHER->value:
                    $groupIds = $stGroups->execute(request()->user()->id);

                    $query->whereIn('id', $groupIds);
                    break;

                case RoleEnum::ADVISER->value:
                    $groupIds = GroupAdviser::where('user_id', request()->user()->id)
                    ->get()
                    ->pluck('group_id');

                    $query->whereIn('id', $groupIds);
                    break;

                case RoleEnum::PANEL->value:
                    $groupIds = GroupPanel::where('user_id', request()->user()->id)
                    ->get()
                    ->pluck('group_id');
                    
                    $query->whereIn('id', $groupIds);
                    break;
                
                default:
                    break;
            }
        })
        ->paginate(10);

        return GroupResource::collection($groups);
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
