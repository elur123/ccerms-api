<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionResource;

use App\Models\Section;
class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::query()
        ->with('teacher', 'sectionType', 'students', 'groups')
        ->get();

        return response()->json([
            'sections' => $sections
        ], 200);
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
        $section->load('teacher', 'sectionType', 'students', 'groups');

        return response()->json([
            'section' => new SectionResource($section)
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
