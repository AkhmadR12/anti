<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWeddingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('wedding'));
    }

    public function rules(): array
    {
        $wedding = $this->route('wedding');

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => [
                'required',
                'string',
                'max:150',
                'alpha_dash',
                Rule::unique('weddings', 'slug')->ignore($wedding->id),
            ],
            'template_id' => ['required', 'exists:templates,id'],
            'status' => ['required', 'in:draft,published,archived'],
        ];
    }
}
