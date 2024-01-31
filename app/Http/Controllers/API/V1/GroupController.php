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
use App\Models\BoardSubmission;
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
    public function show(Group $group, GroupAvailablePersonnelMembers $availablePersonnels)
    {
        $group->load(
            'course', 
            'capstoneType', 
            'groupMilestone.currentMilestone', 
            'groupMilestone.milestone.milestoneList', 
            'members', 
            'advisers', 
            'panels', 
            'statisticians'
        );

        $group->customGroupMilestone = $group->groupMilestone
        ->map(function($milestone) use($group) {

            $milestone->milestone->milestonList = $milestone->milestone->milestoneList
            ->map(function($list) use($group) {
                $lastUpdated = null;
                $submissions = BoardSubmission::query()
                ->whereRelation('board', function($query) use($group, $list) {
                    $query->where('group_id', $group->id)
                    ->where('step_id', $list->id);
                })
                ->latest('updated_at')
                ->first();

                $lastUpdated = $submissions?->updated_at ?? null;

                return [
                    'id' => $list->id,
                    'title' => $list->title,
                    'description' => $list->description,
                    'order_by' => $list->order_by,
                    'percent' => $list->percent,
                    'has_stat' => $list->has_stat,
                    'has_panel' => $list->has_panel,
                    'has_adviser' => $list->has_adviser,
                    'adviser_first' => $list->adviser_first,
                    'last_updated' => $lastUpdated != null ? date('M d, Y H:i:s', strtotime($lastUpdated)) : null,
                ];
            });

            return [
                'id' => $milestone->id,
                'group_id' => $milestone->group_id,
                'capstone_type_id' => $milestone->capstone_type_id,
                'milestone_id' => $milestone->milestone_id,
                'milestone_list_id' => $milestone->milestone_list_id,
                'progress' => $milestone->progress,
                'is_open' => $milestone->is_open,
                'milestone' => $milestone->milestone,
                'currentMilestone' => $milestone->currentMilestone
            ];
        });

        $users_available = $availablePersonnels->execute($group);

        return response()->json([
            'group' => GroupResource::make($group),
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
