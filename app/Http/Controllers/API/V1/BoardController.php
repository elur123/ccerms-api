<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ValidateBoardSubmission;
use App\Services\StoreBoardSubmissionFile;
use App\Services\StoreBoardCommentFile;
use App\Http\Resources\BoardResource;
use App\Http\Resources\BoardSubmissionResource;
use App\Enums\StatusEnum;

use App\Models\Board;
use App\Models\BoardSubmission;
class BoardController extends Controller
{
    
    public function show($group_id, $step_id)
    {
        $boards = Board::query()
        ->with('personnel', 'submissions.status', 'submissions.student', 'submissions.comments.user')
        ->where('group_id', $group_id)
        ->where('step_id', $step_id)
        ->get();

        return response()->json([
            'boards' => BoardResource::collection($boards)
        ], 200);
    }

    public function storeSubmission(
        Request $request, 
        Board $board, 
        ValidateBoardSubmission $validateSubmission,
        StoreBoardSubmissionFile $fileSubmission)
    {
        $request->validate([
            'file' => 'required',
            'comment' => 'required'
        ]);

        if ($validateSubmission->execute($board)) {
            return response()->json([
                'message' => 'Already submitted!'
            ], 422);
        }

        $board->load('personnel', 'submissions.status', 'submissions.student', 'submissions.comments.user');
        $submission = $board->submissions()->create([
            'student_id' => $request->student_id,
            'status_id' => StatusEnum::PENDING->value,
            'details' => $request->comment,
        ]);

        $fileSubmission->execute($request, $submission);

        return $this->show($board->group_id, $board->step_id);
    }

    public function storeSubmissionComment(
        Request $request, 
        BoardSubmission $submission,
        StoreBoardCommentFile $fileComment
    )
    {
        $request->validate([
            'comment' => 'required'
        ]);

        $comment = $submission->comments()->create([
            'comment_by' => $request->comment_by,
            'comment' => $request->comment
        ]);

        $fileComment->execute($request, $comment);

        $submission->load('status', 'student', 'comments.user');

        return response()->json([
            'submission' => BoardSubmissionResource::make($submission)
        ], 200);
    }
}
