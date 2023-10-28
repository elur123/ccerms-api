<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MilestoneListRequest;
use App\Http\Resources\MilestoneListResource;

use App\Models\MilestoneList;
class MilestoneListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $milestoneLists = MilestoneList::query()
        ->with('milestone')
        ->get();

        return response()->json([
            'milestone_lists' => MilestoneListResource::collection($milestoneLists)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MilestoneListRequest $request)
    {
        $list = MilestoneList::create($request->validated());

        return $this->show($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(MilestoneList $milestone_list)
    {

        $milestoneLists = MilestoneList::query()
        ->where('milestone_id', $milestone_list->milestone_id)
        ->with('milestone')
        ->get();

        return response()->json([
            'milestone_lists' => MilestoneListResource::collection($milestoneLists)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MilestoneListRequest $request, MilestoneList $milestone_list)
    {


        $milestone_list->update($request->validated());

        return $this->show($milestone_list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MilestoneList $milestone_list)
    {
        $milestone_list->delete();

        return $this->show($milestone_list);
    }
}
