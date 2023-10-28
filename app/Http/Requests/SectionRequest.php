<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
                
                return $this->postValidation();
                break;
        }
    }

    public function postValidation(): array
    {
        return [
            'section_code' => ['required', 'string', 'max:150'],
            'room_number' => ['required', 'string', 'max:5'],
            'time_start_at' => ['required'],
            'time_end_at' => ['required'],
            'year_start_at' => ['required'],
            'year_end_at' => ['required'],
            'start_at' => ['required'],
            'end_at' => ['required'],
            'section_type_id' => ['required'],
            'capstone_type_id' => ['required'],
            'user_id' => ['required']
        ];
    }
}
