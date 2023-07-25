<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MinuteRequest;
use App\Http\Resources\MinuteResource;

use App\Models\Minute;
class MinuteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $minutes = Minute::query()
        ->with('schedule', 'userPrepared', 'template', 'group', 'contents')
        ->get();

        return response()->json([
            'minutes' => MinuteResource::collection($minutes)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MinuteRequest $request)
    {
        Minute::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(Minute $minute)
    {
        return response()->json([
            'minute' => new MinuteResource($minute)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MinuteRequest $request, Minute $minute)
    {
        $minute->update($request->validated());

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Minute $minute)
    {
        //
    }
}
