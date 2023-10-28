<?php

namespace App\Services;

use App\Models\User;
class GetTeachers {

    public function execute()
    {
        return User::query()
        ->approved()
        ->where('can_teach', true)
        ->get();
    }
}