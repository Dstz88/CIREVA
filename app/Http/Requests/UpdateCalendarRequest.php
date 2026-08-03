<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole(['admin', 'organizer']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'start_datetime' => 'sometimes|required|date',
            'end_datetime' => 'sometimes|required|date|after_or_equal:start_datetime',
            'timezone' => 'nullable|string|max:50',
            'status' => 'nullable|string',
        ];
    }
}
