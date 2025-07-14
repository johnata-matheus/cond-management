<?php

namespace App\Http\Requests\visit;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
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
            'visitor_name' => ['required', 'string', 'max:255'],
            'visitor_phone' => ['nullable', 'string', 'max:20'],
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
            'car_model' => ['nullable', 'string', 'max:100'],
            'car_color' => ['nullable', 'string', 'max:50'],
            'license_plate' => ['nullable', 'string', 'max:10'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
