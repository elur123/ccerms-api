<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use App\Enums\StatusEnum;

class DefenseScheduleRequest extends FormRequest
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
            'venue' => ['required', 'string', 'max:255'],
            'start_at' => ['required'],
            'end_at' => ['required'],
            'schedule_at' => ['required', 'date', 'after_or_equal:today'],
            'type_id' => ['required'],
            'group_id' => [
                'required', 
                Rule::unique('defense_schedules')->where(function (Builder $query)  {
                    return $query->where('group_id', request()->group_id)
                    ->where('status_id', StatusEnum::PENDING->value);
                }),
            ]
        ];
    }

    public function putValidation(): array
    {
        return [
            'venue' => ['required', 'string', 'max:255'],
            'start_at' => ['required'],
            'end_at' => ['required'],
            'schedule_at' => ['required', 'date', 'after_or_equal:today'],
            'type_id' => ['required'],
            'group_id' => [
                'required', 
                Rule::unique('defense_schedules')->where(function (Builder $query)  {
                    return $query->where('group_id', request()->group_id)
                    ->where('status_id', StatusEnum::PENDING->value);
                })->ignore($this->defense->id)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'group_id.unique' => 'Group ID still have schedule with status PENDING'
        ];
    }
}
