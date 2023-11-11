<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\GetGroupBoards;
use App\Services\ValidateBoardSubmission;
use App\Services\StoreBoardSubmissionFile;
use App\Services\StoreBoardCommentFile;
use App\Services\UpdateBoardStatus;
use App\Services\UpdateGroupCurrentStep;
use App\Services\GetBoardRecipientSubmission;
use App\Services\SendSubmissionNotification;
use App\Http\Resources\BoardResource;
use App\Http\Resources\BoardSubmissionResource;
use App\Enums\StatusEnum;

use App\Models\Board;
use App\Models\BoardSubmission;
class BoardController extends Controller
{

    public function index()
    {
        $boards = Board::query()
        ->select('boards.*')
        ->with('group', 'step', 'personnel', 'submissions.status', 'submissions.student', 'submissions.comments.user')
        ->join('groups', 'boards.group_id', 'groups.id')
        ->join('milestone_lists', 'boards.step_id', 'milestone_lists.id')
        ->join('users', 'boards.personnel_id', 'users.id')
        ->filter(request()->s)
        ->has('submissions')
        ->paginate(10);

        return BoardResource::collection($boards);
    }
    
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
        StoreBoardSubmissionFile $fileSubmission,
        GetBoardRecipientSubmission $boardRecipients,
        SendSubmissionNotification $sendNotification
    )
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

        // Notifications
        $recipients = $boardRecipients->execute(['step_id' => $board->step_id, 'group_id' => $board->group_id]);
        $options = [
            'type' => $board->group->group_name.' new submission',
            'message' => request()->user()->name.' submitted step '. $board->step->title,
            'url' => '/admin/groups/show/'.$board->group_id
        ];
        $sendNotification->execute($recipients, $options);

        $board = Board::find($board->id);
        $board->load('personnel', 'submissions.status', 'submissions.student', 'submissions.comments.user');

        return response()->json([
            'board' => BoardResource::make($board)
        ], 200);
    }

    public function storeSubmissionComment(
        Request $request, 
        BoardSubmission $submission,
        StoreBoardCommentFile $fileComment,
        GetBoardRecipientSubmission $boardRecipients,
        SendSubmissionNotification $sendNotification
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

        // Notifications
        $recipients = $boardRecipients->execute(['step_id' => $submission->board->step_id, 'group_id' => $submission->board->group_id]);
        $options = [
            'type' => $submission->board->group->group_name.' new submission comment',
            'message' => request()->user()->name.' commented in this submission '. $submission->board->step->title,
            'url' => '/admin/groups/show/'.$submission->board->group_id
        ];
        $sendNotification->execute($recipients, $options);


        $submission->load('status', 'student', 'comments.user');

        return response()->json([
            'submission' => BoardSubmissionResource::make($submission)
        ], 200);
    }

    public function updateSubmissionStatus(
        Request $request,  
        BoardSubmission $submission,
        UpdateBoardStatus $boardStatus,
        UpdateGroupCurrentStep $groupStep,
        GetBoardRecipientSubmission $boardRecipients,
        SendSubmissionNotification $sendNotification
    )
    {
        $request->validate([
            'progress' => ['required', 'numeric', 'max:100']
        ]);

        $submission->update([
            'status_id' => $request->progress < 100 ? StatusEnum::DECLINED->value : StatusEnum::APPROVED,
            'progress' => doubleval($request->progress),
            'checked_at' => date('Y-m-d H:i:s')
        ]);

        $boardStatus->execute($submission->board_id, $request->progress);

        $board = Board::find($submission->board_id);
        $board->load('personnel', 'submissions.status', 'submissions.student', 'submissions.comments.user');

        $groupStep->execute($board->group_id, $board->step_id);

        // Notifications
        $recipients = $boardRecipients->execute(['step_id' => $submission->board->step_id, 'group_id' => $submission->board->group_id]);
        $options = [
            'type' => $submission->board->group->group_name.' checked submission',
            'message' => request()->user()->name.' checked this submission '. $submission->board->step->title,
            'url' => '/admin/groups/show/'.$submission->board->group_id
        ];
        $sendNotification->execute($recipients, $options);

        $message = ['message' => 'success', 'status' => 200];
        pushMessage($board->group->key, 'boardUpdate', $message);

        return response()->json([
            'board' => BoardResource::make($board)
        ], 200);
    }

    public function download(BoardSubmission $submission)
    {
        if (!Storage::disk('public')->exists(str_replace('public/', '', $submission->file_url))) {
            return [
                'status_code' => 404,
                'messsage' => 'File not found'
            ];
        }

        return Storage::download($submission->file_url);
    }
}
