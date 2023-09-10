<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SettingResource;

use App\Models\Setting;
class SettingController extends Controller
{
    
    public function index()
    {
        return response()->json([
            'settings' => SettingResource::collection(Setting::all())
        ], 200);
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'value' => ['required']
        ]);

        $setting->update([
            'value' => $request->value 
        ]);

        return $this->index();
    }
}
