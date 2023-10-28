<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Board;
class ValidateBoardSubmission {

    public function execute(Board $board)
    {
        return $board->submissions()
        ->where('status_id', StatusEnum::PENDING->value)
        ->exists();
    }
}