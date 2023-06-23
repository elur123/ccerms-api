<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseResource;

use App\Models\Course;
class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::query()
        ->with('milestoneOne', 'milestoneTwo')
        ->get();

        return response()->json([
            'courses' => CourseResource::collection($courses)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        Course::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return response()->json([
            'course' => new CourseResource($courses)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return $this->index();
    }
}
