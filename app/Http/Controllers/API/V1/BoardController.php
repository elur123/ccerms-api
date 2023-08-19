<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BoardResource;
use App\Enums\StatusEnum;

use App\Models\Board;
class BoardController extends Controller
{
    
    public function show($group_id, $step_id)
    {
        $boards = Board::query()
        ->with('personnel', 'submissions')
        ->where('group_id', $group_id)
        ->where('step_id', $step_id)
        ->get();

        return response()->json([
            'boards' => BoardResource::collection($boards)
        ], 200);
    }

    public function storeSubmission(Request $request, Board $board)
    {
        $submission = $board->submission()->store([
            'student_id' => $board->student_id,
            'status_id' => StatusEnum::PENDING->value,
            'details' => $request->comment,
            'file' => 'na',
            'file_url' => 'na',
        ]);

        
    }
}
