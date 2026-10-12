<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeddingEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', $this->route('wedding'));
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:150'],
            'event_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'venue' => ['required', 'string', 'max:200'],
            'address' => ['required', 'string'],
            'maps_url' => ['nullable', 'url'],
            'description' => ['nullable', 'string'],
        ];
    }
}
