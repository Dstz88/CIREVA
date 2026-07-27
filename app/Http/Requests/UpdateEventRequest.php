<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\event;

class UpdateeventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $event = $this->route('event');
        return $event ? $this->user()->can('update', $event) : false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|integer|exists:event_categories,id',
            'location_id' => 'sometimes|integer|exists:event_locations,id',
            'title' => 'sometimes|string|max:200',
            'description' => 'sometimes|string',
            'banner' => 'nullable|string|max:255',
        ];
    }
}
