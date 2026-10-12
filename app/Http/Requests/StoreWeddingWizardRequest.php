<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeddingWizardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Wedding Master
            'title' => ['required', 'string', 'max:200'],
            'template_id' => ['required', 'exists:templates,id'],
            
            // Couple
            'groom_name' => ['required', 'string', 'max:150'],
            'groom_full_name' => ['required', 'string', 'max:200'],
            'groom_nickname' => ['nullable', 'string', 'max:50'],
            'groom_father_name' => ['nullable', 'string', 'max:150'],
            'groom_mother_name' => ['nullable', 'string', 'max:150'],
            'groom_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            'bride_name' => ['required', 'string', 'max:150'],
            'bride_full_name' => ['required', 'string', 'max:200'],
            'bride_nickname' => ['nullable', 'string', 'max:50'],
            'bride_father_name' => ['nullable', 'string', 'max:150'],
            'bride_mother_name' => ['nullable', 'string', 'max:150'],
            'bride_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            // Main Event (Akad Nikah)
            'akad_title' => ['required', 'string', 'max:150'],
            'akad_date' => ['required', 'date'],
            'akad_start_time' => ['required'],
            'akad_end_time' => ['nullable'],
            'akad_venue' => ['required', 'string', 'max:200'],
            'akad_address' => ['required', 'string'],
            'akad_maps_url' => ['nullable', 'url'],

            // Resepsi
            'resepsi_title' => ['nullable', 'string', 'max:150'],
            'resepsi_date' => ['nullable', 'date'],
            'resepsi_start_time' => ['nullable'],
            'resepsi_end_time' => ['nullable'],
            'resepsi_venue' => ['nullable', 'string', 'max:200'],
            'resepsi_address' => ['nullable', 'string'],
            'resepsi_maps_url' => ['nullable', 'url'],

            // Gallery Photos (Optional during wizard)
            'gallery_images' => ['nullable', 'array', 'max:10'],
            'gallery_images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            // Settings
            'opening_text' => ['nullable', 'string'],
            'closing_text' => ['nullable', 'string'],
            'music_url' => ['nullable', 'string', 'max:255'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'font_family' => ['nullable', 'string', 'max:50'],
        ];
    }
}
