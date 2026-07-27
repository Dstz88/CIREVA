<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Booking::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Ensure tickets array is provided and not empty
            'tickets' => 'required|array|min:1',
            // Ensure each item has a valid ticket_id and quantity
            'tickets.*.ticket_id' => 'required|integer|exists:tickets,id',
            'tickets.*.quantity' => 'required|integer|min:1',
        ];
    }
}
