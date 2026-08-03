<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreeventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole(['admin', 'organizer']);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'category_id' => $this->category_id ?? $this->event_category_id,
            'location_id' => $this->location_id ?? $this->event_location_id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:event_categories,id',
            'location_id' => 'required|integer|exists:event_locations,id',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'banner' => 'nullable',
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $locationId = $this->input('location_id') ?? $this->input('event_location_id');
                    $startDate = $value;
                    $endDate = $this->input('end_date') ?? $value;

                    if ($locationId && $startDate) {
                        // Check if another event exists at the same location with overlapping dates
                        $clash = \App\Models\event::where('location_id', $locationId)
                            ->where('status', '!=', \App\Enums\eventStatus::Archived)
                            ->whereHas('schedules', function ($query) use ($startDate, $endDate) {
                                $query->where(function ($q) use ($startDate, $endDate) {
                                    $q->whereBetween('start_datetime', [$startDate, $endDate])
                                        ->orWhereBetween('end_datetime', [$startDate, $endDate])
                                        ->orWhere(function ($sub) use ($startDate, $endDate) {
                                            $sub->where('start_datetime', '<=', $startDate)
                                                ->where('end_datetime', '>=', $endDate);
                                        });
                                });
                            })
                            ->first();

                        if ($clash) {
                            $fail('Jadwal bentrok! Lokasi tersebut sudah digunakan oleh event "' . $clash->title . '" pada rentang waktu yang sama.');
                        }
                    }
                },
            ],
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }
}
