<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;

use App\Models\Notification;
class NotificationController extends Controller
{
    public function index() 
    {
        $notifications = request()->user()->notifications()
        ->where('has_read', false)
        ->latest()
        ->get();

        return response()->json([
            'notifications' => NotificationResource::collection($notifications)
        ]);
    }
    
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

    public function readAll(Request $request)
    {
        $request->user()->notifications()
        ->update([
            'has_read' => true
        ]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
