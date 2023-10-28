<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\NotificationResource;
class HandleNuxtRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $notifications = $user->notifications()
            ->where('has_read', false)
            ->get();
            $response = $next($request);

            // Append user data to the response
            $response->setData(array_merge($response->getData(true), [
                'user' => $user,
                'notifications' => NotificationResource::collection($notifications)
            ]));
            
            return $response;
        }

        return $next($request);
    }
}
