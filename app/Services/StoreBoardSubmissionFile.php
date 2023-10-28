<?php

namespace App\Services;
use Illuminate\Http\Request;

use App\Models\BoardSubmission;
class StoreBoardSubmissionFile {

    public function execute(Request $request, BoardSubmission $submission)
    {

        // Save research file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('public/files/boards/'.$submission->id, $fileName);

            $submission->update([
                'file' => $fileName,
                'file_url' => $filePath
            ]);
        }
    }
}