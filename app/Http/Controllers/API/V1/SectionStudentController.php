<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GetSectionAvailableStudents;
use App\Services\SendActivationEmail;
use App\Services\StoreImportStudent;
use App\Http\Resources\UserResource;
use App\Http\Resources\SectionStudentResource;
use App\Imports\StudentImport;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;

use App\Models\User;
use App\Models\SectionStudent;
use App\Models\Section;
use App\Models\Course;
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

        // Find and create if not exist student in section
        $findSectionStudent = $section->students()
        ->where('user_id', $request->student_id)
        ->count();

        if ($findSectionStudent <= 0) {
            $section->students()->attach($request->student_id, ['status_id' => StatusEnum::ONGOING->value]);
        }

        $section->sectionStudent->load(['student', 'status']);

        return response()->json([
            // 'students' => UserResource::collection($section->students),
            'students' => SectionStudentResource::collection($section->sectionStudent),
            'available' => $availableStudents->execute($params = ['year_end' => $section->year_end_at, 'semester' => $section->section_type_id])
        ], 200);
    }

    public function import(
        Request $request, 
        Section $section, 
        GetSectionAvailableStudents $availableStudents,
        StoreImportStudent $importStudent,
        SendActivationEmail $send
    )
    {

        $request->validate([
            'file' => ['required']
        ]);

        $imported = $importStudent->execute($request->file('file'));

        foreach ($imported as $key => $student) {
            // Find and create if not exist student in section
            $findSectionStudent = $section->students()
            ->where('user_id', $student->id)
            ->count();

            if ($findSectionStudent <= 0) {
                $section->students()->attach($student->id, ['status_id' => StatusEnum::ONGOING->value]);
            }

            // Send email verification
            $send->execute($student);   
        }

        $section = Section::find($section->id);

        $section->sectionStudent->load(['student', 'status']);

        return response()->json([
            // 'students' => UserResource::collection($section->students),
            'students' => SectionStudentResource::collection($section->sectionStudent),
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
