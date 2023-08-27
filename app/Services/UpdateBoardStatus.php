<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Board;
class UpdateBoardStatus {

    public function execute($board_id, $progress)
    {
        Board::where('id', $board_id)
        ->update([
            'status_id' => $progress < 100 ? StatusEnum::DECLINED->value : StatusEnum::APPROVED->value,
            'progress' => $progress
        ]);
    }
}