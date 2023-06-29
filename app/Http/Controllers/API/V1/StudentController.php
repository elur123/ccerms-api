<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserResource;

use App\Models\User;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::query()
        ->student()
        ->get();


        return response()->json([
            'students' => UserResource::collection($students)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        User::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $student)
    {
        return response()->json([
            'student' => new UserResource($student)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegisterRequest $request, User $student)
    {
        $student->update($request->validated());

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
