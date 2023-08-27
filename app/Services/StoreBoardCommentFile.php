<?php

namespace App\Services;
use Illuminate\Http\Request;

use App\Models\BoardComment;
class StoreBoardCommentFile {

    public function execute(Request $request, BoardComment $comment)
    {

        // Save research file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('public/files/boards/'.$comment->submission_id, $fileName);

            $comment->update([
                'file' => $fileName,
                'file_url' => $filePath
            ]);
        }
    }
}