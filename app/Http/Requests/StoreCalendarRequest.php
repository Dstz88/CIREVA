<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && in_array(strtolower((string) $this->user()->role->name), ['admin', 'organizer']);
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('timezone')) {
            $this->merge(['timezone' => 'Asia/Jakarta']);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'event_id' => 'required|integer|exists:events,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'timezone' => 'nullable|string|max:50',
        ];
    }
}
