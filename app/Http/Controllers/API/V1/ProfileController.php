<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;

use App\Models\User;
class ProfileController extends Controller
{
    
    public function select(User $profile)
    {
        return response()->json([
            'profile' => UserResource::make($profile)
        ], 200); 
    }


    public function update(Request $request, User $profile)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($profile->id)],
        ]);

        $profile->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number
        ]);

        if (isset($request->password)) {
            $profile->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('/'.$profile->id, $fileName, 'profiles');

            $profile->update([
                'image_url' => $filePath
            ]);
        }

        return $this->select($profile);
    }
}
