<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResearchArchiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        switch (request()->method()) {
            case 'POST':
                
                return $this->postValidation();
                break;

            case 'PUT':
                
                return $this->putValidation();
                break;
        }
    }

    public function postValidation(): array
    {
        return [
            'group_name' => ['required', Rule::unique('research_archives')],
            'title' => ['required'],
            'file' => ['required', 'mimes:pdf'],
            'keywords' => ['required'],
            'section_year_from' => ['required'],
            'section_year_to' => ['required'],
            'adviser' => ['required'],
            'course_id' => ['required'],
        ];
    }

    public function putValidation(): array
    {
        return [
            'group_name' => ['required', Rule::unique('research_archives')->ignore($this->research_archive->id)],
            'title' => ['required'],
            'file' => ['nullable', 'mimes:pdf'],
            'keywords' => ['required'],
            'section_year_from' => ['required'],
            'section_year_to' => ['required'],
            'adviser' => ['required'],
            'course_id' => ['required'],
        ];
    }
}
