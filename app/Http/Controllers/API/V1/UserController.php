<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Enums\RoleEnum;
use App\Services\GetTeachers;

use App\Models\User;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()
        ->with('role', 'status')
        ->where('role_id', '!=', RoleEnum::STUDENT->value)
        ->where('role_id', '!=', RoleEnum::ADMIN->value)
        ->get();

        return response()->json([
            'users' => UserResource::collection($users)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());
        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function status(Request $request, User $user)
    {
        $user->update([
            'status_id' => $request->status
        ]);
        
        return $this->index();
    }

    public function teachers(GetTeachers $teachers)
    {
        return response()->json([
            'teachers' => $teachers->execute()
        ], 200);
    }
}
