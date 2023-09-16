<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Imports\StudentImport;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;

use App\Models\User;
use App\Models\Course;
class StoreImportStudent {

    public function execute($file)
    {
        $import = new StudentImport;
        ($import)->import($file);


        $students = [];
        foreach ($import->data as $key => $value) {
            if ($key != 0) {
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

                $course = Course::where('course_code', $value['course_code'])->first();
                $course_id = $course?->id ?? null;
                
                // Create student details
                $student->studentDetails()->firstOrCreate(
                    ['user_id' => $student->id],
                    ['course_id' => $course_id, 'student_id' => $value['student_id']]
                );

                array_push($students, $student);
            }
        }

        return $students;
    }
}