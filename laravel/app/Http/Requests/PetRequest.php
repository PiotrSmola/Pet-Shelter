<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:20',
            'species' => 'required|in:Dog,Cat',
            'breed' => 'required|string|max:50',
            'age' => 'required|integer|min:0|max:255',
            'weight' => 'required|numeric|min:0.1|max:999',
            'description' => 'required|string|max:250',
            'photo' => [$this->route('pet') ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }
}
