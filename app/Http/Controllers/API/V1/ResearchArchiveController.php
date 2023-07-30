<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ResearchArchiveRequest;
use App\Http\Resources\ResearchArchiveResource;
use App\Services\SaveResearchArchiveFile;

use App\Models\ResearchArchive;
use App\Models\ResearchArchiveMember;
class ResearchArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $archives = ResearchArchive::query()
        ->with('course', 'members')
        ->filter(request()->s)
        ->paginate();

        return ResearchArchiveResource::collection($archives);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResearchArchiveRequest $request, SaveResearchArchiveFile $service)
    {
        $archive = ResearchArchive::create($request->validated());

        $service->execute($request, $archive);

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(ResearchArchive $research_archive)
    {
        $research_archive->load('course', 'members');

        return response()->json([
            'archive' => ResearchArchiveResource::make($research_archive)
        ], 200); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResearchArchiveRequest $request, ResearchArchive $research_archive, SaveResearchArchiveFile $service)
    {
        $research_archive->update($request->validated());

        $service->execute($request, $research_archive);

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchArchive $research_archive)
    {
        //
    }

    public function addMember(Request $request, ResearchArchive $research_archive)
    {
        $research_archive->members()->create([
            'name' => $request->name
        ]);

        return $this->show($research_archive);
    }

    public function removeMember(ResearchArchiveMember $member)
    {
        $archive = $member->researchArchive;
        $member->delete();

        return $this->show($archive);
    }
}
