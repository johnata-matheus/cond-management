<?php

namespace App\Http\Requests\doorman;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoormanRequest extends FormRequest
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
        return [
            'user_id' => ['required', 'exists:users,id'],
            'badge_number' => ['required', 'string', 'max:20', 'unique:doormen'],
            'phone' => ['nullable', 'string', 'max:20'],
            'shift' => ['required', 'in:morning,afternoon,night'],
            'active' => ['boolean'],
        ];
    }
}
