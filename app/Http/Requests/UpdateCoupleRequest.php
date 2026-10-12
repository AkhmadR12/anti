<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoupleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', $this->route('wedding'));
    }

    public function rules(): array
    {
        return [
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
        ];
    }
}
