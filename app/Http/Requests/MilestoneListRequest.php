<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MilestoneListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'title' => ['required', 'string', 'max:80', Rule::unique('milestone_lists')],
            'description' => ['required', 'string', 'max:1080'],
            'percent' => ['required', 'numeric', 'max:100'],
            'milestone_id' => ['required']
        ];
    }

    public function putValidation(): array
    {
        return [
            'title' => ['required', 'string', 'max:80', Rule::unique('milestone_lists')->ignore($this->milestone_list->id)],
            'description' => ['required', 'string', 'max:1080'],
            'percent' => ['required', 'numeric', 'max:100'],
            'milestone_id' => ['required']
        ];
    }
}
