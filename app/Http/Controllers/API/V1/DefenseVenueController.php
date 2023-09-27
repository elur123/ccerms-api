<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\DefenseVenue;
class DefenseVenueController extends Controller
{
    public function index()
    {
        return response()->json([
            'venues' => DefenseVenue::all()
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => ['required', Rule::unique('defense_venues')]
        ]);

        DefenseVenue::create([
            'label' => $request->label
        ]);

        return $this->index();
    }

    public function update(Request $request, DefenseVenue $defensevenue)
    {
        $request->validate([
            'label' => ['required', Rule::unique('defense_venues')->ignore($defensevenue->id)]
        ]);

        $defensevenue->update([
            'label' => $request->label
        ]);

        return $this->index();
    }

    public function delete(DefenseVenue $defensevenue)
    {
        $defensevenue->delete();
        
        return $this->index();
    }
}
