<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Notification;
class NotificationController extends Controller
{
    public function update(Request $request, Notification $notification)
    {
        $request->user()->notifications()
        ->where('id', $notification->id)
        ->update([
            'has_read' => true
        ]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
