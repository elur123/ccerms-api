<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\Setting;
class UserRequest extends FormRequest
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

        switch (request()->method()) {
            case 'POST':
                
                return $this->postValidation($roles);
                break;

            case 'PUT':
                
                return $this->putValidation($roles);
                break;
        }
    }

    public function postValidation($roles): array
    {
        $setting = Setting::query()
        ->where('key', 'email_extension')
        ->first();

        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'ends_with:'.$setting->value, Rule::unique('users')],
            'password' => ['required', 'string', 'max:8'],
            'role_id' => ['required', Rule::in($roles)],
            'course_id' => ['exclude_unless:role_id,'.RoleEnum::STUDENT->value, 'required'],
            'student_id' => ['exclude_unless:role_id,'.RoleEnum::STUDENT->value, 'required', Rule::unique('student_details')],
            'can_advise' => ['sometimes', 'required', 'boolean'],
            'can_panel' => ['sometimes', 'required', 'boolean'],
            'can_teach' => ['sometimes', 'required', 'boolean']
        ];
    }

    public function putValidation($roles): array
    {
        $setting = Setting::query()
        ->where('key', 'email_extension')
        ->first();

        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email',  'ends_with:'.$setting->value, Rule::unique('users')->ignore($this->student->id ?? $this->user->id)],
            'role_id' => ['required', Rule::in($roles)],
            'course_id' => ['exclude_unless:role_id,'.RoleEnum::STUDENT->value, 'required'],
            'student_id' => ['exclude_unless:role_id,'.RoleEnum::STUDENT->value, 'required', Rule::unique('student_details')->ignore($this->student->id, 'user_id')],
            'can_advise' => ['sometimes', 'required', 'boolean'],
            'can_panel' => ['sometimes', 'required', 'boolean'],
            'can_teach' => ['sometimes', 'required', 'boolean']
        ];
    }
}
