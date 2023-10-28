<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CapstoneTypeRequest;
use App\Http\Resources\CapstoneTypeResource;

use App\Models\CapstoneType;
class CapstoneTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'capstone_types' => CapstoneTypeResource::collection(CapstoneType::all())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CapstoneTypeRequest $request)
    {
        CapstoneType::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(CapstoneType $capstonetype)
    {
        return response()->json([
            'capstone_type' => new CapstoneTypeResource($capstonetype)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CapstoneTypeRequest $request, CapstoneType $capstonetype)
    {
        $capstonetype->update($request->validated());

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
