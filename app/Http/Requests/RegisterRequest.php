<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

use App\Models\Role;
use App\Enums\RoleEnum;
class RegisterRequest extends FormRequest
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
        $roles = Role::query()
        ->where('id', '!=', RoleEnum::ADMIN->value)
        ->get()
        ->pluck('id');
        
        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role_id' => ['required', Rule::in($roles)],
            'course_id' => ['exclude_unless:role_id,'.RoleEnum::STUDENT->value, 'required']
        ];
    }
}
