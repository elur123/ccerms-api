<?php

namespace App\Services;
use Illuminate\Http\Request;

use App\Models\ResearchArchive;
class SaveResearchArchiveFile {

    public function execute(Request $request, ResearchArchive $archive)
    {

        // Save research file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('public/files/archives', $fileName);

            $archive->research_file = $fileName;
            $archive->file_url = $filePath;
            $archive->save();
        }
    }
}