<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\DefenseType;
class DefenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'types' => DefenseType::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => ['required', Rule::unique('defense_types')]
        ]);

        DefenseType::create([
            'label' => $request->label
        ]);

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(DefenseType $defensetype)
    {
        return response()->json([
            'type' => $type
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DefenseType $defensetype)
    {
        $request->validate([
            'label' => ['required', Rule::unique('defense_types')->ignore($defensetype->id)]
        ]);

        $defensetype->update([
            'label' => $request->label
        ]);

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DefenseType $defensetype)
    {
        //
    }
}
