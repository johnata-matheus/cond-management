<?php

namespace App\Http\Requests\doorman;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoormanRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $doormanId = $this->route('doorman')?->id;

        return [
            'user_id' => ['sometimes', 'exists:users,id'],
            'badge_number' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('doormen')->ignore($doormanId)
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'shift' => ['sometimes', 'in:morning,afternoon,night'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
