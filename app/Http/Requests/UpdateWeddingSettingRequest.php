<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeddingSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', $this->route('wedding'));
    }

    public function rules(): array
    {
        return [
            'opening_text' => ['nullable', 'string'],
            'closing_text' => ['nullable', 'string'],
            'music_url' => ['nullable', 'url', 'max:255'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'font_family' => ['nullable', 'string', 'max:50'],
            'show_gift' => ['nullable', 'boolean'],
            'show_rsvp' => ['nullable', 'boolean'],
            'show_guestbook' => ['nullable', 'boolean'],
        ];
    }
}
