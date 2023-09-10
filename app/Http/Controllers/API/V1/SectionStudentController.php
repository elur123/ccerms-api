<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GetSectionAvailableStudents;
use App\Services\SendActivationEmail;
use App\Http\Resources\UserResource;
use App\Imports\StudentImport;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;

use App\Models\User;
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

    public function import(
        Request $request, 
        Section $section, 
        GetSectionAvailableStudents $availableStudents,
        SendActivationEmail $send
    )
    {
        $file = $request->file('file');
        $import = new StudentImport;
        ($import)->import($file);   

        foreach ($import->data as $value) {
            // Create student user
            $student = User::withoutEvents(function () use($value) {
                return User::firstOrCreate(
                    ['email' => $value['email']],
                    [
                        'name' => $value['name'], 
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                        'role_id' => RoleEnum::STUDENT->value, 
                        'status_id' => StatusEnum::PENDING->value,
                    ]
                );
            });

            $course_id = 1;
            
            // Create student details
            $student->studentDetails()->firstOrCreate(
                ['user_id' => $student->id],
                ['course_id' => $course_id, 'student_id' => $value['student_id']]
            );

            // Find and create if not exist studen in section
            $findSectionStudent = $section->students()
            ->where('user_id', $student->id)
            ->count();

            if ($findSectionStudent <= 0) {
                $section->students()->attach($student->id, ['status_id' => StatusEnum::PENDING->value]);
            }

            // Send email verification
            $send->execute($student);
        }

        $section = Section::find($section->id);

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
