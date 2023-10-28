<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MilestoneRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:80', Rule::unique('milestones')],
            'description' => ['required', 'string', 'max:1080'],
            'capstone_type_id' => ['required']
        ];
    }

    public function putValidation(): array
    {
        return [
            'title' => ['required', 'string', 'max:80', Rule::unique('milestones')->ignore($this->milestone->id)],
            'description' => ['required', 'string', 'max:1080'],
            'capstone_type_id' => ['required']
        ];
    }
}
