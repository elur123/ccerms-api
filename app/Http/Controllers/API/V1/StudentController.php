<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;

use App\Models\User;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::query()
        ->with('studentDetails.course', 'status', 'role')
        ->student()
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
        ->paginate(10);


        return UserResource::collection($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $student)
    {
        $student->load('studentDetails.course', 'status', 'role');

        return response()->json([
            'student' => new UserResource($student)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $student)
    {
        $student->update($request->validated());

        if (isset($request->course_id)) 
        {
            $student->studentDetails()->update([
                'course_id' => request()->course_id
            ]);
        }

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function status(Request $request, User $student)
    {
        $student->update([
            'status_id' => $request->status
        ]);
        
        return $this->index();
    }
}
