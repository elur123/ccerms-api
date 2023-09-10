<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SendActivationEmail;
use App\Enums\StatusEnum;

use App\Models\User;
class TestController extends Controller
{
    
    public function sendAccountActivation(SendActivationEmail $send)
    {
        $user = User::where('email', 'dev@umindanao.edu.ph')->first();

        $send->execute($user);

        return ['message' => 'success'];
    }
}
