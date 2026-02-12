<?php

namespace App\Http\Requests;

use App\Rules\NoLocationConflict;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'location_id' => [
                'required',
                'exists:configs,id',
                new NoLocationConflict(
                    $this->location_id,
                    $this->floor_id,
                    $this->event_start_datetime,
                    $this->event_end_datetime
                )
            ],
            'floor_id' => 'required|exists:configs,id',
            'event_start_datetime' => 'required|date|after_or_equal:now',
            'event_end_datetime' => 'nullable|date|after:event_start_datetime',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'event_start_datetime.after_or_equal' => 'Event start datetime cannot be in the past.',
            'event_end_datetime.after' => 'Event end datetime must be after start datetime.',
        ];
    }
}
