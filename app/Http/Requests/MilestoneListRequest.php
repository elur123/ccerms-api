<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Closure;

use App\Models\MilestoneList;
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
        $find = MilestoneList::query()
        ->where([
            'order_by' => request()->order_by,
            'milestone_id' => request()->milestone_id
        ])
        ->first();

        return [
            'title' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:1080'],
            'percent' => ['required', 'numeric', 'max:100'],
            'order_by' => ['required', 'numeric', function (string $attribute, mixed $value, Closure $fail) use ($find) {

                if ($find !== null) {
                    $fail("The order by is invalid.");
                }
            }],
            'adviser_first' => ['boolean'],
            'has_adviser' => ['boolean'],
            'has_panel' => ['boolean'],
            'has_stat' => ['boolean'],
            'milestone_id' => ['required']
        ];
    }

    public function putValidation(): array
    {
        $find = MilestoneList::query()
        ->where([
            ['id', '!=',  request()->milestone_list->id],
            ['order_by', request()->order_by],
            ['milestone_id', request()->milestone_id]
        ])
        ->first();

        return [
            'title' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:1080'],
            'percent' => ['required', 'numeric', 'max:100'],
            'order_by' => ['required', 'numeric', function (string $attribute, mixed $value, Closure $fail) use ($find) {

                if ($find !== null) {
                    $fail("The order by is invalid.");
                }
            }],
            'adviser_first' => ['boolean'],
            'has_adviser' => ['boolean'],
            'has_panel' => ['boolean'],
            'has_stat' => ['boolean'],
            'milestone_id' => ['required']
        ];
    }
}
