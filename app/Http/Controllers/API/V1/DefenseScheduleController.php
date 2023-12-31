<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DefenseScheduleRequest;
use App\Http\Resources\DefenseScheduleResource;
use App\Services\GetAvailableGroups;
use App\Services\UpdateMilestoneStatus;
use App\Enums\StatusEnum;

use App\Models\DefenseSchedule;
class DefenseScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = DefenseSchedule::query()
        ->with('type', 'step', 'group.advisers', 'group.panels', 'group.groupMilestone', 'status', 'panels', 'minute.contents')
        ->get();

        return response()->json([
            'schedules' => DefenseScheduleResource::collection($schedules)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DefenseScheduleRequest $request)
    {
        DefenseSchedule::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(DefenseSchedule $defense)
    {
        $defense->load('type.minuteTemplate.contents', 'group.members', 'group.advisers', 'group.panels', 'group.groupMilestone', 'status', 'panels', 'minute.contents');

        return response()->json([
            'schedule' => new DefenseScheduleResource($defense)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DefenseScheduleRequest $request, DefenseSchedule $defense)
    {
        $defense->update($request->validated());

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DefenseSchedule $defense)
    {
        //
    }

    public function status(Request $request, DefenseSchedule $defense, UpdateMilestoneStatus $milestoneStatus)
    {
        $defense->update([
            'status_id' => $request->status
        ]);

        if ($request->status == StatusEnum::RESCHED->value) {
            $milestoneStatus->execute($defense);
        }

        return $this->index();
    }

    public function availableGroups(GetAvailableGroups $groupAvailable)
    {
        return response()->json([
            'groups' => $groupAvailable->execute()
        ], 200);
    }
}
