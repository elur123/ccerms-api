<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MilestoneRequest;
use App\Http\Resources\MilestoneResource;


use App\Models\Milestone;
class MilestoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $milestones = Milestone::query()
        ->with('capstoneType', 'milestoneList')
        ->get();

        return response()->json([
            'milestones' => MilestoneResource::collection($milestones)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MilestoneRequest $request)
    {
        Milestone::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(Milestone $milestone)
    {
        return response()->json([
            'milestone' => new MilestoneResource($milestone)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MilestoneRequest $request, Milestone $milestone)
    {
        $milestone->update($request->validated());

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
