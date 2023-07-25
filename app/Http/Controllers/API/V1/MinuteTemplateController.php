<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MinuteTemplateRequest;
use App\Http\Resources\MinuteTemplateResource;

use App\Models\MinuteTemplate;
use App\Models\MinuteTemplateContent;
class MinuteTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $minute_templates = MinuteTemplate::query()
        ->with('type', 'contents')
        ->get();

        return response()->json([
            'minute_templates' => $minute_templates
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MinuteTemplateRequest $request)
    {
        MinuteTemplate::create($request->validated());

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(MinuteTemplate $minutetemplate)
    {
        $minutetemplate->load('type', 'contents');

        return response()->json([
            'minute_template' => $minutetemplate
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MinuteTemplateRequest $request, MinuteTemplate $minutetemplate)
    {
        $minutetemplate->update($request->validated());

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MinuteTemplate $minutetemplate)
    {
        //
    }

    public function addContent(Request $request, MinuteTemplate $template)
    {
        $template->contents()->create([
            'label' => $request->label,
            'order' => $request->order
        ]);

        return $this->show($template);
    }

    public function removeContent(MinuteTemplateContent $content)
    {
        $template = $content->minuteTemplate;

        $content->delete();

        return $this->show($template);
    }
}
