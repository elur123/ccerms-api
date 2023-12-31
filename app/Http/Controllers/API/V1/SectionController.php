<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionResource;
use App\Enums\RoleEnum;

use App\Models\Section;
class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::query()
        ->with('teacher', 'sectionType', 'capstoneType', 'sectionStudent.status', 'sectionStudent.student', 'groups.groupMilestone', 'status')
        ->filter(request()->s)
        ->when(request()->orderBy && request()->orderFunction, function ($query) {
            $query->orderBy(request()->orderBy, request()->orderFunction);   
        })
        ->when(request()->user()->role_id, function($query) {
            if (request()->user()->role_id == RoleEnum::SUBJECT_TEACHER->value) {
                $query->where('user_id', request()->user()->id);
            }
        })
        ->paginate(10);

        return SectionResource::collection($sections);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request)
    {
        Section::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        $section->load('teacher', 'sectionType', 'capstoneType', 'sectionStudent.status', 'sectionStudent.student', 'groups.groupMilestone', 'status');

        return response()->json([
            'section' => SectionResource::make($section)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, Section $section)
    {
        $section->update($request->validated());

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        //
    }
}
