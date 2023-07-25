<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GetSectionAvailableStudents;
use App\Http\Resources\UserResource;

use App\Models\SectionStudent;
use App\Models\Section;
class SectionStudentController extends Controller
{
    
    public function availableStudents(Request $request, GetSectionAvailableStudents $availableStudents)
    {
        return response()->json([
            'available' => $availableStudents->execute($request->all())
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Section $section, GetSectionAvailableStudents $availableStudents)
    {
        $request->validate([
            'student_id' => 'required'
        ]);

        SectionStudent::create([
            'section_id' => $section->id,
            'user_id' => $request->student_id
        ]);

        return response()->json([
            'students' => UserResource::collection($section->students),
            'available' => $availableStudents->execute($params = ['year_end' => $section->year_end_at, 'semester' => $section->section_type_id])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section, $student_id, GetSectionAvailableStudents $availableStudents)
    {
        SectionStudent::where('section_id', $section->id)
        ->where('user_id', $student_id)
        ->delete();

        return response()->json([
            'students' => UserResource::collection($section->students),
            'available' => $availableStudents->execute($params = ['year_end' => $section->year_end_at, 'semester' => $section->section_type_id])
        ], 200); 
    }
}
