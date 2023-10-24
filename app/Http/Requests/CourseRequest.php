<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
            'course_code' => ['required', 'string', 'max:50', Rule::unique('courses')],
            'label' => ['required', 'string', 'max:50', Rule::unique('courses')],
            'milestone_one' => ['required'],
            'milestone_two' => ['required']
        ];
    }

    public function putValidation(): array
    {
        return [
            'course_code' => ['required', 'string', 'max:50', Rule::unique('courses')->ignore($this->course->id)],
            'label' => ['required', 'string', 'max:50', Rule::unique('courses')->ignore($this->course->id)],
            'milestone_one' => ['required'],
            'milestone_two' => ['required']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'key' => request()->course_code
        ]);
    }
}
