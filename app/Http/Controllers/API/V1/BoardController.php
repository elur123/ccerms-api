<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GetGroupBoards;
use App\Services\ValidateBoardSubmission;
use App\Services\StoreBoardSubmissionFile;
use App\Services\StoreBoardCommentFile;
use App\Services\UpdateBoardStatus;
use App\Services\UpdateGroupCurrentStep;
use App\Http\Resources\BoardResource;
use App\Http\Resources\BoardSubmissionResource;
use App\Enums\StatusEnum;

use App\Models\Board;
use App\Models\BoardSubmission;
class BoardController extends Controller
{
    
    public function show($group_id, $step_id, GetGroupBoards $groupBoards)
    {
        $boards = $groupBoards->execute($group_id, $step_id);

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

        $revision = $board->submissions()->count();

        $submission = $board->submissions()->create([
            'student_id' => $request->student_id,
            'status_id' => StatusEnum::PENDING->value,
            'details' => $request->comment,
            'revision' => $revision + 1
        ]);

        $fileSubmission->execute($request, $submission);

        $board = Board::find($board->id);
        $board->load('personnel', 'submissions.status', 'submissions.student', 'submissions.comments.user');

        return response()->json([
            'board' => BoardResource::make($board)
        ], 200);
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

    public function updateSubmissionStatus(
        Request $request,  
        BoardSubmission $submission,
        UpdateBoardStatus $boardStatus,
        UpdateGroupCurrentStep $groupStep
    )
    {
        $request->validate([
            'progress' => 'required'
        ]);

        $submission->update([
            'status_id' => $request->progress < 100 ? StatusEnum::DECLINED->value : StatusEnum::APPROVED,
            'progress' => doubleval($request->progress)
        ]);

        $boardStatus->execute($submission->board_id, $request->progress);

        $board = Board::find($submission->board_id);
        $board->load('personnel', 'submissions.status', 'submissions.student', 'submissions.comments.user');

        $groupStep->execute($board->group_id, $board->step_id);

        return response()->json([
            'board' => BoardResource::make($board)
        ], 200);
    }
}
