<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Board;
class UpdateBoardStatus {

    public function execute($board_id, $progress, $status)
    {
        Board::where('id', $board_id)
        ->update([
            'status_id' => $status,
            'progress' => $progress
        ]);
    }
}