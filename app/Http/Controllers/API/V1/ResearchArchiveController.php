<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ResearchArchiveRequest;
use App\Http\Resources\ResearchArchiveResource;

use App\Models\ResearchArchive;
class ResearchArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $archives = ResearchArchive::query()
        ->with('course', 'members')
        ->paginate();

        return ResearchArchiveResource::collection($archives);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResearchArchiveRequest $request)
    {
        ResearchArchive::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(ResearchArchive $research_archive)
    {
        $research_archive->load('couse', 'members');
        return response()->json([
            'archives' => ResearchArchiveResource::make($research_archive)
        ], 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResearchArchiveRequest $request, ResearchArchive $research_archive)
    {
        $research_archive->update($request->validated());

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchArchive $research_archive)
    {
        //
    }
}
