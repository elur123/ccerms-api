<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StoreBoardSubmissionFile;
use App\Http\Resources\BoardResource;
use App\Enums\StatusEnum;

use App\Models\Board;
class BoardController extends Controller
{
    
    public function show($group_id, $step_id)
    {
        $boards = Board::query()
        ->with('personnel', 'submissions.status', 'submissions.student')
        ->where('group_id', $group_id)
        ->where('step_id', $step_id)
        ->get();

        return response()->json([
            'boards' => BoardResource::collection($boards)
        ], 200);
    }

    public function storeSubmission(Request $request, Board $board, StoreBoardSubmissionFile $fileSubmission)
    {
        $request->validate([
            'file' => 'required',
            'comment' => 'required'
        ]);

        $board->load('personnel', 'submissions.status', 'submissions.student');
        $submission = $board->submissions()->create([
            'student_id' => $request->student_id,
            'status_id' => StatusEnum::PENDING->value,
            'details' => $request->comment,
        ]);

        $fileSubmission->execute($request, $submission);

        return $this->show($board->group_id, $board->step_id);
    }
}
