<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
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
            'group_name' =>  ['required', 'string', 'max:80', Rule::unique('groups')],
            'title' => ['required', 'string', 'max:1040', Rule::unique('groups')],
            'course_id' => ['required'],
            'capstone_type_id' => ['required']
        ];
    }

    public function putValidation(): array
    {
        return [
            'group_name' =>  ['required', 'string', 'max:80', Rule::unique('groups')->ignore($this->group->id)],
            'title' => ['required', 'string', 'max:1040', Rule::unique('groups')->ignore($this->group->id)],
            'course_id' => ['required'],
            'capstone_type_id' => ['required']
        ];
    }
}
