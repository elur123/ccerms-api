<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\GroupResource;
use App\Services\GetSectionAvailableGroups;

use App\Models\SectionGroup;
use App\Models\Section;
class SectionGroupController extends Controller
{

    public function availableGroups(Request $request, GetSectionAvailableGroups $avialableGroups)
    {
        return response()->json([
            'available' => $avialableGroups->execute($request->all())
        ], 200);    
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Section $section, GetSectionAvailableGroups $avialableGroups)
    {
        $request->validate([
            'group_id' => 'required'
        ]);

        SectionGroup::create([
            'section_id' => $section->id,
            'group_id' => $request->group_id
        ]);

        $section->groups->load('groupMilestone');

        $params = ['year_end' => $section->year_end_at, 'semester' => $section->section_type_id, 'capstone_type' => $section->capstone_type_id];
        return response()->json([
            'groups' => GroupResource::collection($section->groups),
            'available' => $avialableGroups->execute($params)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section, $group_id, GetSectionAvailableGroups $avialableGroups)
    {

        SectionGroup::where('section_id', $section->id)
        ->where('group_id', $group_id)
        ->delete();

        $section->groups->load('groupMilestone');

        $params = ['year_end' => $section->year_end_at, 'semester' => $section->section_type_id, 'capstone_type' => $section->capstone_type_id];

        return response()->json([
            'groups' => GroupResource::collection($section->groups),
            'available' => $avialableGroups->execute($params)
        ], 200);
    }
}
