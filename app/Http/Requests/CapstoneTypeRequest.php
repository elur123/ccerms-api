<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CapstoneTypeRequest extends FormRequest
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
            'label' => ['required', 'string', 'max:50', Rule::unique('capstone_types')]
        ];
    }

    public function putValidation(): array
    {
        return [
            'label' => ['required', 'string', 'max:50', Rule::unique('capstone_types')->ignore($this->capstonetype->id)]
        ];
    }
}
